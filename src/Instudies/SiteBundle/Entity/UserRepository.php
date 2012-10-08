<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\EntityRepository,
    Symfony\Component\Security\Core\User\UserProviderInterface,
    Symfony\Component\Security\Core\User\UserInterface,
    Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface
;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{

	public function totalUsers ()
	{

		return $this->getEntityManager()
			->createQuery('
					SELECT
						count(user.id) as userCount
					FROM InstudiesSiteBundle:User user
				')
			->getSingleScalarResult();

	}

    public function totalUndeletedUsers()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->add('select', $qb->expr()->count('user.id').'  as count_user');
        $qb->from("InstudiesSiteBundle:User", "user");
        $qb->where($qb->expr()->eq('user.deleted', '0'));
        $q = $qb->getQuery();
        return $q->getSingleScalarResult();
    }

    public function totalActiveUsers(array $active_groups_ids)
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P2M'));

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->add('select', $qb->expr()->count('user.id').'  as count_user');
        $qb->from("InstudiesSiteBundle:User", "user");
        $qb->leftJoin('user.groups', 'userEducationGroup');
        $qb->innerJoin('userEducationGroup.educationGroup', 'educationGroup');
        $qb->where(
            $qb->expr()->andX(
                $qb->expr()->eq('user.deleted', '0'),
                $qb->expr()->eq('user.emailActivated', '1'),
                $qb->expr()->eq('user.filledAllInformation', '1'),
                $qb->expr()->gt('user.lastVisit', ':date'),
                $qb->expr()->in('educationGroup.id', $active_groups_ids)
            )
        );
        $qb->setParameter('date', $date);
        $q = $qb->getQuery();
        return $q->getSingleScalarResult();
    }

	public function user($user)
	{

		$newMessagesCount = $this->getEntityManager()
			->createQuery('
					SELECT
						count(message.id) as newMessagesCount
					FROM InstudiesSiteBundle:Message message
					WHERE
						message.reciever = :userId
						AND (message.readed = false OR message.readed IS NULL)
				')
			->setParameter('userId', $user->getId())
			->getSingleScalarResult();

		$newNotificationsCount = $this->getEntityManager()
			->createQuery('
					SELECT
						count(notification.id) as newNotificationsCount
					FROM InstudiesSiteBundle:Notification notification
					WHERE
						notification.reciever = :userId
				')
			->setParameter('userId', $user->getId())
			->getSingleScalarResult();

		return array(
				'messages' => $newMessagesCount,
				'notifications' => $newNotificationsCount
			);

		return false;		

	}

	public function findByRole($role)
	{

		return $this->getEntityManager()
					->createQuery("
						SELECT
							user
						FROM InstudiesSiteBundle:User user
						JOIN user.userRoles role
							WITH role.name = :role
					")
					->setParameter('role', $role)
					->getResult();

	}

	public function inerlocutors($user)
	{

		if (count($user->getGroups()) > 0) {

			$idsCondition = '';
			$counter=0;
			foreach ($user->getGroups() as $group) {
				$counter++;
				$idsCondition .= 'userGroup.educationGroup = '.$group->getEducationGroup()->getId();
				if ($counter != count($user->getGroups())) {
					$idsCondition .= ' OR ';
				}
			}

			return $this->getEntityManager()
						->createQuery("
							SELECT
								user,
								userGroup
							FROM InstudiesSiteBundle:User user
							JOIN user.groups as userGroup
								WITH userGroup.user = user.id AND (".$idsCondition.")
							LEFT JOIN userGroup.user userGroupUser
							WHERE
								user.id != :userId
							ORDER BY userGroupUser.firstname ASC, userGroupUser.lastname ASC
						")
						->setParameter('userId', $user->getId())
						->getResult();

		}

		return array();

	}

	function loadUser(UserInterface $user)
	{
		return $user;
	}

	function loadUserByUsername($email)
	{
		return $this->getEntityManager()
			->createQuery('
					SELECT u FROM
					InstudiesSiteBundle:User u
					WHERE u.email = :email
				')
			->setParameters(array(
					'email' => $email
				))
			->getOneOrNullResult();
	}

	function loadUserByAccount(AccountInterface $account)
	{
		return $this->loadUserByUsername($account->getName());
	}

	public function refreshUser(UserInterface $user) {
		return $this->loadUserByUsername($account->getName());
	}

	public function supportsClass($class) {
		return $class === 'InstudiesSiteBundle\Entity\User';
	}

	public function usersListWothoutMe ($educationGroupId, $userId) {

		$query = $this->getEntityManager()
					->createQuery("
						SELECT
							userEducationGroup,
							user,
							count(DISTINCT myFavourite.id) as myFavouriteCount
						FROM InstudiesSiteBundle:UserEducationGroup userEducationGroup
						LEFT JOIN userEducationGroup.user user
						LEFT JOIN user.favourites myFavourite WITH myFavourite.owner = :userId
						WHERE userEducationGroup.educationGroup = :educationGroupId
						AND userEducationGroup.user != :userId
						GROUP BY userEducationGroup.id
						ORDER BY user.firstname ASC, user.lastname ASC
					");

		$result = $query
			->setParameter('educationGroupId', $educationGroupId)
			->setParameter('userId', $userId)
			->getResult();

		$newResult = array();

		foreach ($result as $r) {
			if ($r['myFavouriteCount'] > 0) {
				$r[0]->getUser()->inMyFavorites = true;
			} else {
				$r[0]->getUser()->inMyFavorites = false;
			}
			$newResult[] = $r[0];
		}

		return $newResult;

	}

	public function usersList ($educationGroupId, $userId) {

		$query = $this->getEntityManager()
					->createQuery("
						SELECT
							userEducationGroup,
							user,
							count(DISTINCT myFavourite.id) as myFavouriteCount
						FROM InstudiesSiteBundle:UserEducationGroup userEducationGroup
						LEFT JOIN userEducationGroup.user user
						LEFT JOIN user.favourites myFavourite WITH myFavourite.owner = :userId
						WHERE userEducationGroup.educationGroup = :educationGroupId
						GROUP BY userEducationGroup.id
						ORDER BY userEducationGroup.created DESC
					");

		$result = $query
			->setParameter('educationGroupId', $educationGroupId)
			->setParameter('userId', $userId)
			->getResult();

		$newResult = array();

		foreach ($result as $r) {
			if ($r['myFavouriteCount'] > 0) {
				$r[0]->getUser()->inMyFavorites = true;
			} else {
				$r[0]->getUser()->inMyFavorites = false;
			}
			$newResult[] = $r[0];
		}

		return $newResult;

	}

	public function findOnlineInChat($educationGroupId) {

		$date = new \DateTime();
		$date->sub(new \DateInterval('PT120S'));

		return $this->getEntityManager()
			->createQuery("
				SELECT
					user,
					userEducationGroup
				FROM InstudiesSiteBundle:UserEducationGroup userEducationGroup
				LEFT JOIN userEducationGroup.user user
				WHERE userEducationGroup.lastChatActivity > :date
				AND userEducationGroup.educationGroup = :educationGroupId
				GROUP BY user.id, userEducationGroup.id
			")
			->setParameter('educationGroupId', $educationGroupId)
			->setParameter('date', $date)
			->getResult();

	}

	public function findOnlineInEducationGroup($educationGroupId) {

		$date = new \DateTime();
		$date->sub(new \DateInterval('PT600S'));

		return $this->getEntityManager()
			->createQuery("
				SELECT
					user,
					userEducationGroup
				FROM InstudiesSiteBundle:UserEducationGroup userEducationGroup
				LEFT JOIN userEducationGroup.user user
				WHERE user.lastVisit > :date
				AND userEducationGroup.educationGroup = :educationGroupId
				GROUP BY user.id, userEducationGroup.id
			")
			->setParameter('educationGroupId', $educationGroupId)
			->setParameter('date', $date)
			->getResult();

	}

    public function counters ($userId, $watcherId)
    {

		if ($userId != $watcherId) {

			$groupIds = $this->getEntityManager()->getRepository('InstudiesSiteBundle:User')->groupMateIds($userId, $watcherId);

			$groupBlogIdsCondition = " AND (( ";
			$groupEventIdsCondition = " AND ( ";
			$groupHomeworkIdsCondition = " AND ( ";
			$groupSummaryIdsCondition = " AND ( ";
			$groupCommentIdsCondition = " AND ( ";

			$counter=0;
			foreach ($groupIds as $groupId) {
				$counter++;
				$groupBlogIdsCondition .= " blog.educationGroup = " . $groupId['id'] . " ";
				$groupEventIdsCondition .= " event.educationGroup = " . $groupId['id'] . " ";
				$groupHomeworkIdsCondition .= " homework.educationGroup = " . $groupId['id'] . " ";
				$groupSummaryIdsCondition .= " summary.educationGroup = " . $groupId['id'] . " ";
				$groupCommentIdsCondition .= " comment.educationGroup = " . $groupId['id'] . " ";
				if ($counter != count($groupIds)) {
					$groupBlogIdsCondition .=" OR ";
					$groupEventIdsCondition .=" OR ";
					$groupHomeworkIdsCondition .=" OR ";
					$groupSummaryIdsCondition .=" OR ";
					$groupCommentIdsCondition .= " OR ";
				}
			}

			$groupBlogIdsCondition .= ") OR (blog.educationGroupAssociated = false)) ";
			$groupEventIdsCondition .= ") ";
			$groupHomeworkIdsCondition .= ") ";
			$groupSummaryIdsCondition .= ") ";
			$groupCommentIdsCondition .= ") ";

		} else {

			$groupBlogIdsCondition = "";
			$groupEventIdsCondition = "";
			$groupHomeworkIdsCondition = "";
			$groupSummaryIdsCondition = "";
			$groupCommentIdsCondition = "";

		}

        return $this->getEntityManager()
            ->createQuery("
                SELECT
                    (SELECT COUNT(blog.id) FROM InstudiesSiteBundle:EducationGroupBlogPost blog WHERE blog.user = user.id " . $groupBlogIdsCondition . " AND (blog.deleted = false OR blog.deleted IS NULL) ) as blogCount,
                    (SELECT COUNT(event.id) FROM InstudiesSiteBundle:EducationGroupEventPost event WHERE event.user = user.id " . $groupEventIdsCondition . " ) as eventCount,
                    (SELECT COUNT(homework.id) FROM InstudiesSiteBundle:EducationGroupHomeworkPost homework WHERE homework.user = user.id " . $groupHomeworkIdsCondition . " ) as homeworkCount,
                    (SELECT COUNT(summary.id) FROM InstudiesSiteBundle:EducationGroupSummaryPost summary WHERE summary.user = user.id " . $groupSummaryIdsCondition . " ) as summaryCount,
                    (SELECT COUNT(comment.id) FROM InstudiesSiteBundle:Comment comment WHERE comment.user = user.id " . $groupCommentIdsCondition . " ) as commentCount
                FROM
                    InstudiesSiteBundle:User user
                WHERE user.id = :userId
                GROUP BY user.id
            ")
            ->setParameter('userId', $userId)
            ->getOneOrNullResult();

    }

    public function groupMates ($user1, $user2) {

    	$query = $this->getEntityManager()
    		->createQuery("
    			SELECT
    				count(user2ugr.id) as connectedGroupCount
    			FROM
    				InstudiesSiteBundle:User user1
    			LEFT JOIN user1.groups user1ugr
    			LEFT JOIN user1ugr.educationGroup user1group
    			LEFT JOIN user1group.users user2ugr
    				WITH user2ugr.user = :user2Id
    			WHERE user1.id = :user1Id
    		")
    		->setParameter('user1Id', $user1->getId())
    		->setParameter('user2Id', $user2->getId())
    		->getSingleScalarResult();

    	return $query;

    }

    public function groupMateIds ($user1, $user2) 
    {
    	return $this->getEntityManager()
						->createQuery("
			    			SELECT
			    				user1group.id
			    			FROM
			    				InstudiesSiteBundle:User user1
			    			LEFT JOIN user1.groups user1ugr
			    			LEFT JOIN user1ugr.educationGroup user1group
			    			JOIN user1group.users user2ugr
			    				WITH user2ugr.user = :user2Id
			    			WHERE user1.id = :user1Id
			    			GROUP BY user1group.id
						")
			    		->setParameter('user1Id', $user1)
			    		->setParameter('user2Id', $user2)
						->getResult();
    }

    public function updatePassword(User $user, EncoderFactoryInterface $encoderFactory)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            if (!$user->getSalt()){
                $user->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
            }
            $encoder = $encoderFactory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
        }
    }

    public function save(User $user)
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    public function delete(User $user){
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}