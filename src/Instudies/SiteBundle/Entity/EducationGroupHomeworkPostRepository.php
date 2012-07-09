<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository,
	Instudies\SiteBundle\Helper\PagedQuery
;

/**
 * EducationGroupHomeworkPostRepository
 */
class EducationGroupHomeworkPostRepository extends EntityRepository
{

	public function countPosts ($groupId, $userId)
	{

		$date = new \DateTime();
		$date->sub(new \DateInterval('P1D'));

        return $this->getEntityManager()
            ->createQuery("
                SELECT
					(
						SELECT
							COUNT(actualPost.id)
						FROM InstudiesSiteBundle:EducationGroupHomeworkPost actualPost
						JOIN actualPost.subject actualPostSubject
						WHERE
							actualPost.educationGroup = :groupId
							AND
							(actualPost.deleted = false OR actualPost.deleted IS NULL)
							AND
							(actualPostSubject.archived IS NULL OR actualPostSubject.archived = false)
							AND
							actualPost.date >= :date
					) as actualPostsCount,
					(
						SELECT
							COUNT(archivedPost.id)
						FROM InstudiesSiteBundle:EducationGroupHomeworkPost archivedPost
						LEFT JOIN archivedPost.subject archivedPostSubject
						WHERE
							archivedPost.educationGroup = :groupId
							AND
							(archivedPost.deleted = false OR archivedPost.deleted IS NULL)
							AND
							(archivedPostSubject.archived = true OR archivedPost.date < :date OR archivedPost.subject IS NULL)
					) as archivedPostsCount,
					(
						SELECT
							COUNT(favourite.id)
						FROM InstudiesSiteBundle:Favourite favourite
						JOIN favourite.educationGroupHomeworkPost favouritePost WITH favouritePost.educationGroup = :groupId AND (favouritePost.deleted = false OR favouritePost.deleted IS NULL)
						WHERE favourite.owner = :userId
					) as favouritePostsCount,
					(
						SELECT
							COUNT(deletedPost.id)
						FROM InstudiesSiteBundle:EducationGroupHomeworkPost deletedPost
						WHERE
							deletedPost.educationGroup = :groupId AND deletedPost.deleted = true
					) as deletedPostsCount
           		FROM InstudiesSiteBundle:EducationGroup egucationGroup
           		WHERE egucationGroup.id = :groupId
            ")
            ->setParameter('userId', $userId)
            ->setParameter('groupId', $groupId)
	        ->setParameter('date', $date)
            ->getOneOrNullResult();

	}

	public function findOneByIdFull ($postId, $userId)
	{

		$result = $this->getEntityManager()
					->createQuery("
						SELECT

							post,
							subject,
							user,
							attachments,

							(
								SELECT
									COUNT(myFavourite.id)
								FROM InstudiesSiteBundle:Favourite myFavourite
								WHERE
									myFavourite.owner = :userId
									AND
									myFavourite.educationGroupHomeworkPost = post.id
							) as myFavouriteCount,

							(
								SELECT
									COUNT(comment.id)
								FROM InstudiesSiteBundle:Comment comment
								WHERE
									comment.educationGroupHomeworkPost = post.id
							) as commentsCount,

							(
								SELECT
									COUNT(readedComment.id)
								FROM InstudiesSiteBundle:Comment readedComment
								JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
								WHERE
									readedComment.educationGroupHomeworkPost = post.id
								GROUP BY post.id
							) as readedCommentCount,

							(
								SELECT
									COUNT(unreaded)
								FROM InstudiesSiteBundle:UnreadedEducationGroupHomeworkPost unreaded
								WHERE
									unreaded.educationGroupHomeworkPost = post.id
									AND
									unreaded.user = :userId
							) as unreadedCount,

							(
								SELECT
									COUNT(creationActivityUnreaded)
								FROM InstudiesSiteBundle:UnreadedActivity creationActivityUnreaded
								WHERE
									creationActivityUnreaded.activity = creationActivity.id
									AND
									creationActivityUnreaded.user = :userId
							) as creationActivityUnreadedCount

						FROM InstudiesSiteBundle:EducationGroupHomeworkPost post
						LEFT JOIN post.user user
						LEFT JOIN post.subject subject
						LEFT JOIN post.attachments attachments
						LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupHomeworkPost = post.id
						WHERE post.id = :postId
						GROUP BY post.id, attachments.id
					")
					->setParameter('userId', $userId)
					->setParameter('postId', $postId)
					->getOneOrNullResult();

		if (count($result[0]) > 0) {

			if ($result['myFavouriteCount'] > 0) {
				$result[0]->inMyFavorites = true;
			} else {
				$result[0]->inMyFavorites = false;
			}

			if ($result['unreadedCount'] > 0) {
				$result[0]->isReaded = false;
			} else {
				$result[0]->isReaded = true;
			}

			if ($result['creationActivityUnreadedCount'] > 0) {
				$result[0]->creationActivityIsReaded = false;
			} else {
				$result[0]->creationActivityIsReaded = true;
			}

			$result[0]->commentsNum = $result['commentsCount'];

			$result[0]->commentsNewNum = $result[0]->commentsNum - $result['readedCommentCount'];

			return $result[0];

		}

		return false;

	}

	public function findByEducationGroupAndDate ($educationGroupId, $date)
	{

		return $this->getEntityManager()
				->createQuery("
					SELECT post, subject FROM
					InstudiesSiteBundle:EducationGroupHomeworkPost post
					JOIN post.subject subject
					WHERE post.educationGroup = :educationGroupId
					AND post.date = :date
					AND (post.deleted = false OR post.deleted IS NULL)
					ORDER BY post.created DESC
				")
				->setParameter('educationGroupId', $educationGroupId)
				->setParameter('date', $date)
				->getResult();

	}

	public function findClosestByEducationGroup ($educationGroupId, $maximum = 5)
	{

		$date = new \DateTime();
		$date->sub(new \DateInterval('P1D'));

		return $this->getEntityManager()
				->createQuery("
					SELECT post, subject FROM
					InstudiesSiteBundle:EducationGroupHomeworkPost post
					JOIN post.subject subject
					WHERE post.educationGroup = :educationGroupId
					AND (subject.archived = :archived OR subject.archived IS NULL)
					AND post.date > :date
					AND (post.deleted = false OR post.deleted IS NULL)
					ORDER BY post.date ASC
				")
				->setParameter('educationGroupId', $educationGroupId)
				->setParameter('archived', false)
				->setParameter('date', $date)
				->setMaxResults($maximum)
				->getResult();

	}

	public function findByEducationGroup ($educationGroupId, $userId, $filter = "actual", $subjectId = null, $page = 1, $perPage = 10)
	{

		$date = new \DateTime();
		$date->sub(new \DateInterval('P1D'));

		if ($subjectId == null) {
			$condition = "";
		} else {
			$condition = " AND post.subject = " . $subjectId;
			$condition2 = "post.date";
			$condition3 = "ASC";
		}

		if ($filter == "actual") {

			$condition2 = "post.date";
			$condition3 = "ASC";

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:EducationGroupHomeworkPost post
					JOIN post.subject subject
					WHERE
					post.educationGroup = :educationGroupId
					AND
					(post.deleted = false OR post.deleted IS NULL)
					AND
					((subject.archived = false OR subject.archived IS NULL) AND post.date >= :date) 
					".$condition."
					GROUP BY post.id
					ORDER BY ".$condition2." ".$condition3."
				";

			$idsQueryParameters =
				array(
						'educationGroupId' => $educationGroupId,
						'date' => $date
					);

		} elseif ($filter == "archived") {

			$condition2 = "post.date";
			$condition3 = "DESC";

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:EducationGroupHomeworkPost post
					LEFT JOIN post.subject subject
					WHERE
					post.educationGroup = :educationGroupId
					AND
					(post.deleted = false OR post.deleted IS NULL)
					AND
					(subject.archived = true OR post.date < :date OR post.subject IS NULL) 
					".$condition."
					GROUP BY post.id
					ORDER BY ".$condition2." ".$condition3."
				";

			$idsQueryParameters =
				array(
						'educationGroupId' => $educationGroupId,
						'date' => $date
					);

		} elseif ($filter == "trash") {

			$condition2 = "post.date";
			$condition3 = "DESC";

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:EducationGroupHomeworkPost post
					WHERE (post.educationGroup = :educationGroupId)
					".$condition."
					AND post.deleted = true
					GROUP BY post.id
					ORDER BY ".$condition2." ".$condition3."
				";

			$idsQueryParameters =
				array(
						'educationGroupId' => $educationGroupId
					);

		} elseif ($filter == "favourites") {

			$condition2 = "post.date";
			$condition3 = "DESC";

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:Favourite favourite
					JOIN favourite.educationGroupHomeworkPost post WITH post.educationGroup = :educationGroupId AND (post.deleted = false OR post.deleted IS NULL)
					WHERE favourite.owner = :userId
					GROUP BY post.id
					ORDER BY ".$condition2." ".$condition3."
				";

			$idsQueryParameters =
				array(
						'educationGroupId' => $educationGroupId,
						'userId' => $userId
					);

		}

		$itemsQuery =
			"
				SELECT
					post,
					subject,
					user,
					attachments,

							(
								SELECT
									COUNT(myFavourite.id)
								FROM InstudiesSiteBundle:Favourite myFavourite
								WHERE
									myFavourite.owner = :userId
									AND
									myFavourite.educationGroupHomeworkPost = post.id
							) as myFavouriteCount,

							(
								SELECT
									COUNT(comment.id)
								FROM InstudiesSiteBundle:Comment comment
								WHERE
									comment.educationGroupHomeworkPost = post.id
							) as commentsCount,

							(
								SELECT
									COUNT(readedComment.id)
								FROM InstudiesSiteBundle:Comment readedComment
								JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
								WHERE
									readedComment.educationGroupHomeworkPost = post.id
								GROUP BY post.id
							) as readedCommentCount,

							(
								SELECT
									COUNT(unreaded)
								FROM InstudiesSiteBundle:UnreadedEducationGroupHomeworkPost unreaded
								WHERE
									unreaded.educationGroupHomeworkPost = post.id
									AND
									unreaded.user = :userId
							) as unreadedCount,

							(
								SELECT
									COUNT(creationActivityUnreaded)
								FROM InstudiesSiteBundle:UnreadedActivity creationActivityUnreaded
								WHERE
									creationActivityUnreaded.activity = creationActivity.id
									AND
									creationActivityUnreaded.user = :userId
							) as creationActivityUnreadedCount

				FROM InstudiesSiteBundle:EducationGroupHomeworkPost post
				LEFT JOIN post.user user
				LEFT JOIN post.subject subject
				LEFT JOIN post.attachments attachments
				LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupHomeworkPost = post.id
				WHERE %ids_condition%
				GROUP BY post.id, attachments.id
				ORDER BY ".$condition2." ".$condition3."
			";

		$itemsQueryParameters =
			array(
					'userId' => $userId
				);

		$pagedQuery = new PagedQuery(
				$this->getEntityManager(),
				$page,
				$perPage,
				'post',
				$idsQuery,
				$idsQueryParameters,
				$itemsQuery,
				$itemsQueryParameters
			);

		$result = $pagedQuery->getResult();

		$newResult = array();
		$postsByDates = array();

		if ($result) {

			foreach ($result as $r) {

				if ($r['myFavouriteCount'] > 0) {
					$r[0]->inMyFavorites = true;
				}

				if ($r['unreadedCount'] > 0) {
					$r[0]->isReaded = false;
				} else {
					$r[0]->isReaded = true;
				}

				if ($r['creationActivityUnreadedCount'] > 0) {
					$r[0]->creationActivityIsReaded = false;
				} else {
					$r[0]->creationActivityIsReaded = true;
				}

				$r[0]->commentsNum = $r['commentsCount'];

				$r[0]->commentsNewNum = $r[0]->commentsNum - $r['readedCommentCount'];

				$newResult[] = $r[0];

			}

		    foreach ($newResult as $post) {

		        if (array_key_exists($post->getDate()->getTimestamp() . '', $postsByDates)) {
		            $postsByDates[$post->getDate()->getTimestamp() . ''][] = $post;
		        } else {
		            $postsByDates[$post->getDate()->getTimestamp() . ''] = array();
		            $postsByDates[$post->getDate()->getTimestamp() . ''][] = $post;
		        }

		    }

		}

		return array(
				'postsByDates' => $postsByDates,
				'next' => $pagedQuery->getNext()
			);

	}

	public function findByUser ($userId, $watcherId, $page = 1, $perPage = 10)
	{

		if ($userId != $watcherId) {

			$groupIds = $this->getEntityManager()->getRepository('InstudiesSiteBundle:User')->groupMateIds($userId, $watcherId);

			$groupIdsCondition = " AND ( ";

			$counter=0;
			foreach ($groupIds as $groupId) {
				$counter++;
				$groupIdsCondition .= " post.educationGroup = " . $groupId['id'] . " ";
				if ($counter != count($groupIds)) {
					$groupIdsCondition .= " OR ";
				}
			}

			$groupIdsCondition .= ") ";

		} else {

			$groupIdsCondition = "";

		}

		$idsQuery =
			"
				SELECT
					DISTINCT post.id
				FROM InstudiesSiteBundle:EducationGroupHomeworkPost post
				LEFT JOIN post.subject subject
				WHERE post.user = :userId ". $groupIdsCondition ."
				GROUP BY post.id
				ORDER BY post.created DESC
			";

		$idsQueryParameters =
			array(
					'userId' => $userId
				);

		$itemsQuery =
			"
				SELECT

					post,
					subject,
					user,
					attachments,

							(
								SELECT
									COUNT(myFavourite.id)
								FROM InstudiesSiteBundle:Favourite myFavourite
								WHERE
									myFavourite.owner = :userId
									AND
									myFavourite.educationGroupHomeworkPost = post.id
							) as myFavouriteCount,

							(
								SELECT
									COUNT(comment.id)
								FROM InstudiesSiteBundle:Comment comment
								WHERE
									comment.educationGroupHomeworkPost = post.id
							) as commentsCount,

							(
								SELECT
									COUNT(readedComment.id)
								FROM InstudiesSiteBundle:Comment readedComment
								JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
								WHERE
									readedComment.educationGroupHomeworkPost = post.id
								GROUP BY post.id
							) as readedCommentCount,

							(
								SELECT
									COUNT(unreaded)
								FROM InstudiesSiteBundle:UnreadedEducationGroupHomeworkPost unreaded
								WHERE
									unreaded.educationGroupHomeworkPost = post.id
									AND
									unreaded.user = :userId
							) as unreadedCount,

							(
								SELECT
									COUNT(creationActivityUnreaded)
								FROM InstudiesSiteBundle:UnreadedActivity creationActivityUnreaded
								WHERE
									creationActivityUnreaded.activity = creationActivity.id
									AND
									creationActivityUnreaded.user = :userId
							) as creationActivityUnreadedCount

				FROM InstudiesSiteBundle:EducationGroupHomeworkPost post
				LEFT JOIN post.user user
				LEFT JOIN post.subject subject
				LEFT JOIN post.attachments attachments
				LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupHomeworkPost = post.id
				WHERE %ids_condition%
				GROUP BY post.id, attachments.id
				ORDER BY post.created DESC
			";

		$itemsQueryParameters =
			array(
					'userId' => $userId
				);

		$pagedQuery = new PagedQuery(
				$this->getEntityManager(),
				$page,
				$perPage,
				'post',
				$idsQuery,
				$idsQueryParameters,
				$itemsQuery,
				$itemsQueryParameters
			);

		$result = $pagedQuery->getResult();

		$newResult = array();

		if ($result) {

			foreach ($result as $r) {
				if ($r['myFavouriteCount'] > 0) {
					$r[0]->inMyFavorites = true;
				}
				$r[0]->commentsNum = $r['commentsCount'];
				$r[0]->commentsNewNum = $r[0]->commentsNum - $r['readedCommentCount'];
				$newResult[] = $r[0];
			}

		}

		return array(
				'posts' => $newResult,
				'next' => $pagedQuery->getNext()
			);

	}

	public function creationActivity ($post)
	{
		return $this->getEntityManager()
            ->createQuery('
                    SELECT a FROM
                    InstudiesSiteBundle:Activity a
                    WHERE a.type = :type
                    AND a.educationGroupHomeworkPost = :postId
                ')
            ->setParameter('postId', $post->getId())
            ->setParameter('type', "EDUCATION_GROUP_NEW_HOMEWORKPOST")
            ->getOneOrNullResult();

	}

}