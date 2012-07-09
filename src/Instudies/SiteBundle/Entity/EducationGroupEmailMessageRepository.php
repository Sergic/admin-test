<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository,
	Instudies\SiteBundle\Helper\PagedQuery
;

/**
 * EducationGroupEmailMessageRepository
 */
class EducationGroupEmailMessageRepository extends EntityRepository
{

	public function countPosts ($groupId, $userId)
	{

        return $this->getEntityManager()
            ->createQuery("
                SELECT
					(
						SELECT
							COUNT(actualPost.id)
						FROM InstudiesSiteBundle:EducationGroupEmailMessage actualPost
						WHERE
							actualPost.educationGroup = :groupId
							AND
							(actualPost.deleted = false OR actualPost.deleted IS NULL)
					) as actualPostsCount,
					(
						SELECT
							COUNT(favourite.id)
						FROM InstudiesSiteBundle:Favourite favourite
						JOIN favourite.educationGroupEmailMessage favouritePost WITH favouritePost.educationGroup = :groupId AND (favouritePost.deleted = false OR favouritePost.deleted IS NULL)
						WHERE favourite.owner = :userId
					) as favouritePostsCount,
					(
						SELECT
							COUNT(deletedPost.id)
						FROM InstudiesSiteBundle:EducationGroupEmailMessage deletedPost
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
							attachments,

							(
								SELECT
									COUNT(myFavourite.id)
								FROM InstudiesSiteBundle:Favourite myFavourite
								WHERE
									myFavourite.owner = :userId
									AND
									myFavourite.educationGroupEmailMessage = post.id
							) as myFavouriteCount,

							(
								SELECT
									COUNT(comment.id)
								FROM InstudiesSiteBundle:Comment comment
								WHERE
									comment.educationGroupEmailMessage = post.id
							) as commentsCount,

							(
								SELECT
									COUNT(readedComment.id)
								FROM InstudiesSiteBundle:Comment readedComment
								JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
								WHERE
									readedComment.educationGroupEmailMessage = post.id
								GROUP BY post.id
							) as readedCommentCount,

							(
								SELECT
									COUNT(unreaded)
								FROM InstudiesSiteBundle:UnreadedEducationGroupEmailMessage unreaded
								WHERE
									unreaded.educationGroupEmailMessage = post.id
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
							) as creationActivityUnreadedCount,

							educationGroupBlogPost,
							educationGroupHomeworkPost,
							educationGroupEventPost,
							educationGroupSummaryPost

						FROM InstudiesSiteBundle:EducationGroupEmailMessage post
						LEFT JOIN post.attachments attachments
						LEFT JOIN post.educationGroupBlogPost educationGroupBlogPost
						LEFT JOIN post.educationGroupHomeworkPost educationGroupHomeworkPost
						LEFT JOIN post.educationGroupEventPost educationGroupEventPost
						LEFT JOIN post.educationGroupSummaryPost educationGroupSummaryPost
						LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupEmailMessage = post.id
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
					FROM InstudiesSiteBundle:EducationGroupEmailMessage post
					WHERE post.educationGroup = :educationGroupId
					".$condition."
					AND
					(post.deleted IS NULL or post.deleted = false)
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
					FROM InstudiesSiteBundle:EducationGroupEmailMessage post
					WHERE post.educationGroup = :educationGroupId
					".$condition."
					AND
					post.deleted = true
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
					JOIN favourite.educationGroupEmailMessage post WITH post.educationGroup = :educationGroupId AND (post.deleted = false OR post.deleted IS NULL)
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
					attachments,

					(
						SELECT
							COUNT(myFavourite.id)
						FROM InstudiesSiteBundle:Favourite myFavourite
						WHERE
							myFavourite.owner = :userId
							AND
							myFavourite.educationGroupEmailMessage = post.id
					) as myFavouriteCount,

					(
						SELECT
							COUNT(comment.id)
						FROM InstudiesSiteBundle:Comment comment
						WHERE
							comment.educationGroupEmailMessage = post.id
					) as commentsCount,

					(
						SELECT
							COUNT(readedComment.id)
						FROM InstudiesSiteBundle:Comment readedComment
						JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
						WHERE
							readedComment.educationGroupEmailMessage = post.id
						GROUP BY post.id
					) as readedCommentCount,

					(
						SELECT
							COUNT(unreaded)
						FROM InstudiesSiteBundle:UnreadedEducationGroupEmailMessage unreaded
						WHERE
							unreaded.educationGroupEmailMessage = post.id
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
					) as creationActivityUnreadedCount,

					educationGroupBlogPost,
					educationGroupHomeworkPost,
					educationGroupEventPost,
					educationGroupSummaryPost

				FROM InstudiesSiteBundle:EducationGroupEmailMessage post
				LEFT JOIN post.attachments attachments
				LEFT JOIN post.educationGroupBlogPost educationGroupBlogPost
				LEFT JOIN post.educationGroupHomeworkPost educationGroupHomeworkPost
				LEFT JOIN post.educationGroupEventPost educationGroupEventPost
				LEFT JOIN post.educationGroupSummaryPost educationGroupSummaryPost
				LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupEmailMessage = post.id
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
                    AND a.educationGroupEmailMessage = :postId
                ')
            ->setParameter('postId', $post->getId())
            ->setParameter('type', "EDUCATION_GROUP_NEW_EMAILMESSAGE")
            ->getOneOrNullResult();

	}

}