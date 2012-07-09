<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository,
	Instudies\SiteBundle\Helper\PagedQuery
;

use
    Instudies\SiteBundle\Entity\ReadedEducationGroupBlogPost
;

/**
 * EducationGroupBlogPostRepository
 */
class EducationGroupBlogPostRepository extends EntityRepository
{

	public function read ($user, $item)
	{

		if (count($item) > 0 && count($user) > 0) {

			if ($item->getEducationGroupAssociated()) {

				$unreaded = $this->getEntityManager()->getRepository('InstudiesSiteBundle:UnreadedEducationGroupBlogPost')->findBy(array('user' => $user->getId(), 'educationGroupBlogPost' => $item->getId()));

				foreach ($unreaded as $u) {
					$this->getEntityManager()->remove($u);
				}

			} else {

				$readed = new ReadedEducationGroupBlogPost();
				$readed->setUser($user);
				$readed->setEducationGroupBlogPost($item);

				$this->getEntityManager()->persist($readed);

			}

			$this->getEntityManager()->flush();

		}

	}

	public function countPosts ($groupId, $userId)
	{

        return $this->getEntityManager()
            ->createQuery("
                SELECT
					(
						SELECT
							COUNT(actualPost.id)
						FROM InstudiesSiteBundle:EducationGroupBlogPost actualPost
						WHERE
							(actualPost.educationGroup = :groupId OR actualPost.educationGroupAssociated = false)
							AND (actualPost.deleted = false OR actualPost.deleted IS NULL)
					) as actualPostsCount,
					(
						SELECT
							COUNT(favourite.id)
						FROM InstudiesSiteBundle:Favourite favourite
						JOIN favourite.educationGroupBlogPost favouritePost WITH (favouritePost.educationGroup = :groupId OR favouritePost.educationGroupAssociated = false) AND (favouritePost.deleted = false OR favouritePost.deleted IS NULL)
						WHERE favourite.owner = :userId
					) as favouritePostsCount,
					(
						SELECT
							COUNT(deletedPost.id)
						FROM InstudiesSiteBundle:EducationGroupBlogPost deletedPost
						WHERE
							deletedPost.educationGroup = :groupId AND deletedPost.deleted = true
					) as deletedPostsCount
           		FROM InstudiesSiteBundle:EducationGroup egucationGroup
           		WHERE egucationGroup.id = :groupId
            ")
            ->setParameter('userId', $userId)
            ->setParameter('groupId', $groupId)
            ->getOneOrNullResult();

	}

	public function findOneByIdFull ($postId, $userId)
	{

		$result = $this->getEntityManager()
					->createQuery("
						SELECT
							post,
							creationActivity.educationGroupAssociated as creationActivityEducationGroupAssociated,
							category,
							user,
							attachments,

							(
								SELECT
									COUNT(myFavourite.id)
								FROM InstudiesSiteBundle:Favourite myFavourite
								WHERE
									myFavourite.owner = :userId
									AND
									myFavourite.educationGroupBlogPost = post.id
							) as myFavouriteCount,

							(
								SELECT
									COUNT(comment.id)
								FROM InstudiesSiteBundle:Comment comment
								WHERE
									comment.educationGroupBlogPost = post.id
							) as commentsCount,

							(
								SELECT
									COUNT(readedComment.id)
								FROM InstudiesSiteBundle:Comment readedComment
								JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
								WHERE
									readedComment.educationGroupBlogPost = post.id
								GROUP BY post.id
							) as readedCommentCount,

							(
								SELECT
									COUNT(unreaded)
								FROM InstudiesSiteBundle:UnreadedEducationGroupBlogPost unreaded
								WHERE
									unreaded.educationGroupBlogPost = post.id
									AND
									unreaded.user = :userId
							) as unreadedCount,

							(
								SELECT
									COUNT(readed)
								FROM InstudiesSiteBundle:ReadedEducationGroupBlogPost readed
								WHERE
									post.educationGroupAssociated = false
									AND
									readed.educationGroupBlogPost = post.id
									AND
									readed.user = :userId
							) as readedCount,

							(
								SELECT
									COUNT(creationActivityUnreaded)
								FROM InstudiesSiteBundle:UnreadedActivity creationActivityUnreaded
								WHERE
									creationActivityUnreaded.activity = creationActivity.id
									AND
									creationActivityUnreaded.user = :userId
							) as creationActivityUnreadedCount,

							(
								SELECT
									COUNT(creationActivityReaded)
								FROM InstudiesSiteBundle:ReadedActivity creationActivityReaded
								WHERE
									creationActivity.educationGroupAssociated = false
									AND
									creationActivityReaded.activity = creationActivity.id
									AND
									creationActivityReaded.user = :userId
							) as creationActivityReadedCount

						FROM InstudiesSiteBundle:EducationGroupBlogPost post
						LEFT JOIN post.user user
						LEFT JOIN post.category category
						LEFT JOIN post.attachments attachments
						LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupBlogPost = post.id
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

			if ($result[0]->getEducationGroupAssociated()) {

				if ($result['unreadedCount'] > 0) {
					$result[0]->isReaded = false;
				} else {
					$result[0]->isReaded = true;
				}

			} else {

				if ($result['readedCount'] > 0) {
					$result[0]->isReaded = true;
				} else {
					$result[0]->isReaded = false;
				}

			}

			if ($result['creationActivityEducationGroupAssociated']) {
				if ($result['creationActivityUnreadedCount'] > 0) {
					$result[0]->creationActivityIsReaded = false;
				} else {
					$result[0]->creationActivityIsReaded = true;
				}
			} else {
				if ($result['creationActivityReadedCount'] > 0) {
					$result[0]->creationActivityIsReaded = true;
				} else {
					$result[0]->creationActivityIsReaded = false;
				}
			}

			$result[0]->commentsNum = $result['commentsCount'];

			$result[0]->commentsNewNum = $result[0]->commentsNum - $result['readedCommentCount'];

			return $result[0];

		}

		return false;

	}

	public function findByEducationGroup ($educationGroupId, $userId, $filter = "actual", $categoryId = null, $page = 1, $perPage = 10)
	{

		if ($categoryId == null) {
			$condition = "";
		} else {
			$condition = " AND post.category = " . $categoryId;
		}

		if ($filter == "actual") {

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:EducationGroupBlogPost post
					WHERE (post.educationGroup = :educationGroupId OR post.educationGroupAssociated = false)
					".$condition."
					AND (post.deleted = false OR post.deleted IS NULL)
					GROUP BY post.id
					ORDER BY post.created DESC
				";

			$idsQueryParameters =
				array(
						'educationGroupId' => $educationGroupId
					);

		} elseif ($filter == "trash") {

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:EducationGroupBlogPost post
					WHERE (post.educationGroup = :educationGroupId)
					".$condition."
					AND post.deleted = true
					GROUP BY post.id
					ORDER BY post.created DESC
				";

			$idsQueryParameters =
				array(
						'educationGroupId' => $educationGroupId
					);

		} elseif ($filter == "favourites") {

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:Favourite favourite
					JOIN favourite.educationGroupBlogPost post WITH (post.educationGroup = :educationGroupId OR post.educationGroupAssociated = false) AND (post.deleted = false OR post.deleted IS NULL)
					WHERE favourite.owner = :userId
					GROUP BY post.id
					ORDER BY post.created DESC
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
					creationActivity.educationGroupAssociated as creationActivityEducationGroupAssociated,
					category,
					user,
					attachments,

					(
						SELECT
							COUNT(myFavourite.id)
						FROM InstudiesSiteBundle:Favourite myFavourite
						WHERE
							myFavourite.owner = :userId
							AND
							myFavourite.educationGroupBlogPost = post.id
					) as myFavouriteCount,

					(
						SELECT
							COUNT(comment.id)
						FROM InstudiesSiteBundle:Comment comment
						WHERE
							comment.educationGroupBlogPost = post.id
					) as commentsCount,

					(
						SELECT
							COUNT(readedComment.id)
						FROM InstudiesSiteBundle:Comment readedComment
						JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
						WHERE
							readedComment.educationGroupBlogPost = post.id
						GROUP BY post.id
					) as readedCommentCount,

					(
						SELECT
							COUNT(unreaded)
						FROM InstudiesSiteBundle:UnreadedEducationGroupBlogPost unreaded
						WHERE
							post.educationGroupAssociated = true
							AND
							unreaded.educationGroupBlogPost = post.id
							AND
							unreaded.user = :userId
					) as unreadedCount,

					(
						SELECT
							COUNT(readed)
						FROM InstudiesSiteBundle:ReadedEducationGroupBlogPost readed
						WHERE
							post.educationGroupAssociated = false
							AND
							readed.educationGroupBlogPost = post.id
							AND
							readed.user = :userId
					) as readedCount,

					(
						SELECT
							COUNT(creationActivityUnreaded)
						FROM InstudiesSiteBundle:UnreadedActivity creationActivityUnreaded
						WHERE
							creationActivity.educationGroupAssociated = true
							AND
							creationActivityUnreaded.activity = creationActivity.id
							AND
							creationActivityUnreaded.user = :userId
					) as creationActivityUnreadedCount,

					(
						SELECT
							COUNT(creationActivityReaded)
						FROM InstudiesSiteBundle:ReadedActivity creationActivityReaded
						WHERE
							creationActivity.educationGroupAssociated = false
							AND
							creationActivityReaded.activity = creationActivity.id
							AND
							creationActivityReaded.user = :userId
					) as creationActivityReadedCount

				FROM InstudiesSiteBundle:EducationGroupBlogPost post
				LEFT JOIN post.user user
				LEFT JOIN post.category category
				LEFT JOIN post.attachments attachments
				LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupBlogPost = post.id
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

				if ($r[0]->getEducationGroupAssociated()) {

					if ($r['unreadedCount'] > 0) {
						$r[0]->isReaded = false;
					} else {
						$r[0]->isReaded = true;
					}

				} else {

					if ($r['readedCount'] > 0) {
						$r[0]->isReaded = true;
					} else {
						$r[0]->isReaded = false;
					}

				}

				if ($r['creationActivityEducationGroupAssociated']) {
					if ($r['creationActivityUnreadedCount'] > 0) {
						$r[0]->creationActivityIsReaded = false;
					} else {
						$r[0]->creationActivityIsReaded = true;
					}
				} else {
					if ($r['creationActivityReadedCount'] > 0) {
						$r[0]->creationActivityIsReaded = true;
					} else {
						$r[0]->creationActivityIsReaded = false;
					}
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

	public function findByUser ($userId, $watcherId, $page = 1, $perPage = 10)
	{

		if ($userId != $watcherId) {

			$groupIds = $this->getEntityManager()->getRepository('InstudiesSiteBundle:User')->groupMateIds($userId, $watcherId);

			$groupIdsCondition = " AND (( ";

			$counter=0;
			foreach ($groupIds as $groupId) {
				$counter++;
				$groupIdsCondition .= " post.educationGroup = " . $groupId['id'] . " ";
				if ($counter != count($groupIds)) {
					$groupIdsCondition .= " OR ";
				}
			}

			$groupIdsCondition .= ") OR (post.educationGroupAssociated = false)) ";

		} else {

			$groupIdsCondition = "";

		}

		$idsQuery =
			"
				SELECT
					DISTINCT post.id
				FROM InstudiesSiteBundle:EducationGroupBlogPost post
				WHERE post.user = :userId ". $groupIdsCondition ." AND (post.deleted = false OR post.deleted IS NULL)
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
					creationActivity.educationGroupAssociated as creationActivityEducationGroupAssociated,
					category,
					user,
					attachments,

					(
						SELECT
							COUNT(myFavourite.id)
						FROM InstudiesSiteBundle:Favourite myFavourite
						WHERE
							myFavourite.owner = :userId
							AND
							myFavourite.educationGroupBlogPost = post.id
					) as myFavouriteCount,

					(
						SELECT
							COUNT(comment.id)
						FROM InstudiesSiteBundle:Comment comment
						WHERE
							comment.educationGroupBlogPost = post.id
					) as commentsCount,

					(
						SELECT
							COUNT(readedComment.id)
						FROM InstudiesSiteBundle:Comment readedComment
						JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
						WHERE
							readedComment.educationGroupBlogPost = post.id
						GROUP BY post.id
					) as readedCommentCount,

					(
						SELECT
							COUNT(unreaded)
						FROM InstudiesSiteBundle:UnreadedEducationGroupBlogPost unreaded
						WHERE
							unreaded.educationGroupBlogPost = post.id
							AND
							unreaded.user = :userId
					) as unreadedCount,

					(
						SELECT
							COUNT(readed)
						FROM InstudiesSiteBundle:ReadedEducationGroupBlogPost readed
						WHERE
							post.educationGroupAssociated = false
							AND
							readed.educationGroupBlogPost = post.id
							AND
							readed.user = :userId
					) as readedCount,

					(
						SELECT
							COUNT(creationActivityUnreaded)
						FROM InstudiesSiteBundle:UnreadedActivity creationActivityUnreaded
						WHERE
							creationActivityUnreaded.activity = creationActivity.id
							AND
							creationActivityUnreaded.user = :userId
					) as creationActivityUnreadedCount,

					(
						SELECT
							COUNT(creationActivityReaded)
						FROM InstudiesSiteBundle:ReadedActivity creationActivityReaded
						WHERE
							creationActivity.educationGroupAssociated = false
							AND
							creationActivityReaded.activity = creationActivity.id
							AND
							creationActivityReaded.user = :userId
					) as creationActivityReadedCount

				FROM InstudiesSiteBundle:EducationGroupBlogPost post
				LEFT JOIN post.user user
				LEFT JOIN post.category category
				LEFT JOIN post.attachments attachments
				LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupBlogPost = post.id
				WHERE %ids_condition%
				GROUP BY post.id, attachments.id
				ORDER BY post.created DESC
			";

		$itemsQueryParameters =
			array(
					'userId' => $watcherId
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

				if ($r[0]->getEducationGroupAssociated()) {

					if ($r['unreadedCount'] > 0) {
						$r[0]->isReaded = false;
					} else {
						$r[0]->isReaded = true;
					}

				} else {

					if ($r['readedCount'] > 0) {
						$r[0]->isReaded = true;
					} else {
						$r[0]->isReaded = false;
					}

				}

				if ($r['creationActivityEducationGroupAssociated']) {
					if ($r['creationActivityUnreadedCount'] > 0) {
						$r[0]->creationActivityIsReaded = false;
					} else {
						$r[0]->creationActivityIsReaded = true;
					}
				} else {
					if ($r['creationActivityReadedCount'] > 0) {
						$r[0]->creationActivityIsReaded = true;
					} else {
						$r[0]->creationActivityIsReaded = false;
					}
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
                    AND a.educationGroupBlogPost = :postId
                ')
            ->setParameter('postId', $post->getId())
            ->setParameter('type', "EDUCATION_GROUP_NEW_BLOGPOST")
            ->getOneOrNullResult();

	}

}