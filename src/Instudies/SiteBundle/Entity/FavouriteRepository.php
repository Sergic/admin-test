<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository,
	Instudies\SiteBundle\Helper\PagedQuery
;

class FavouriteRepository extends EntityRepository
{

    public function counters ($userId)
    {

        return $this->getEntityManager()
            ->createQuery("
                SELECT
                    COUNT(DISTINCT blog.id) as blogCount,
                    COUNT(DISTINCT event.id) as eventCount,
                    COUNT(DISTINCT homework.id) as homeworkCount,
                    COUNT(DISTINCT summary.id) as summaryCount,
                    COUNT(DISTINCT message.id) as messageCount,
                    COUNT(DISTINCT email.id) as emailCount,
                    COUNT(DISTINCT comment.id) as commentCount
                FROM
                    InstudiesSiteBundle:Favourite favourite
                LEFT JOIN favourite.educationGroupBlogPost blog
                LEFT JOIN favourite.educationGroupEventPost event
                LEFT JOIN favourite.educationGroupHomeworkPost homework
                LEFT JOIN favourite.educationGroupSummaryPost summary
                LEFT JOIN favourite.educationGroupEmailMessage email
                LEFT JOIN favourite.message message
                LEFT JOIN favourite.comment comment
                WHERE
					favourite.owner = :userId
            ")
            ->setParameter('userId', $userId)
            ->getOneOrNullResult();

    }

	public function findByFull($type, $userId, $page = 1, $perPage = 10)
	{

		if ($type == 'EducationGroupEmailMessage') {

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:EducationGroupEmailMessage post
					JOIN post.favourites myFavourite WITH myFavourite.owner = :userId
					GROUP BY post.id
					ORDER BY myFavourite.created DESC
				";

			$idsQueryParameters =
				array(
						'userId' => $userId
					);

			$itemsQuery =
				"
					SELECT

						post,
						attachments,

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
					) as creationActivityUnreadedCount

					FROM InstudiesSiteBundle:EducationGroupEmailMessage post
					JOIN post.favourites myFavourite
						WITH myFavourite.owner = :userId AND myFavourite.educationGroupEmailMessage = post.id
					LEFT JOIN post.attachments attachments
					LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupEmailMessage = post.id
					WHERE %ids_condition%
					GROUP BY post.id, attachments.id
					ORDER BY myFavourite.created DESC
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

					$r[0]->inMyFavorites = true;

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

		if ($type == 'EducationGroupBlogPost') {

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:EducationGroupBlogPost post
					JOIN post.favourites myFavourite WITH myFavourite.owner = :userId
					GROUP BY post.id
					ORDER BY myFavourite.created DESC
				";

			$idsQueryParameters =
				array(
						'userId' => $userId
					);

			$itemsQuery =
				"
					SELECT

						post,
						category,
						user,
						attachments,

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
							COUNT(creationActivityUnreaded)
						FROM InstudiesSiteBundle:UnreadedActivity creationActivityUnreaded
						WHERE
							creationActivityUnreaded.activity = creationActivity.id
							AND
							creationActivityUnreaded.user = :userId
					) as creationActivityUnreadedCount

					FROM InstudiesSiteBundle:EducationGroupBlogPost post
					JOIN post.favourites myFavourite
						WITH myFavourite.owner = :userId AND myFavourite.educationGroupBlogPost = post.id
					LEFT JOIN post.user user
					LEFT JOIN post.category category
					LEFT JOIN post.attachments attachments
					LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupBlogPost = post.id
					WHERE %ids_condition%
					GROUP BY post.id, attachments.id
					ORDER BY myFavourite.created DESC
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

					$r[0]->inMyFavorites = true;

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

		if ($type == 'EducationGroupEventPost') {

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:EducationGroupEventPost post
					JOIN post.favourites myFavourite WITH myFavourite.owner = :userId
					GROUP BY post.id
					ORDER BY myFavourite.created DESC
				";

			$idsQueryParameters =
				array(
						'userId' => $userId
					);

			$itemsQuery =
				"
					SELECT

						post,
						category,
						user,
						attachments,

					(
						SELECT
							COUNT(comment.id)
						FROM InstudiesSiteBundle:Comment comment
						WHERE
							comment.educationGroupEventPost = post.id
					) as commentsCount,

					(
						SELECT
							COUNT(readedComment.id)
						FROM InstudiesSiteBundle:Comment readedComment
						JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
						WHERE
							readedComment.educationGroupEventPost = post.id
						GROUP BY post.id
					) as readedCommentCount,

					(
						SELECT
							COUNT(unreaded)
						FROM InstudiesSiteBundle:UnreadedEducationGroupEventPost unreaded
						WHERE
							unreaded.educationGroupEventPost = post.id
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
					FROM InstudiesSiteBundle:EducationGroupEventPost post
					JOIN post.favourites myFavourite
						WITH myFavourite.owner = :userId AND myFavourite.educationGroupEventPost = post.id
					LEFT JOIN post.user user
					LEFT JOIN post.category category
					LEFT JOIN post.attachments attachments
					LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupEventPost = post.id
					WHERE %ids_condition%
					GROUP BY post.id, attachments.id
					ORDER BY myFavourite.created DESC
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

					$r[0]->inMyFavorites = true;

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

		if ($type == 'EducationGroupHomeworkPost') {

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:EducationGroupHomeworkPost post
					JOIN post.favourites myFavourite WITH myFavourite.owner = :userId
					GROUP BY post.id
					ORDER BY myFavourite.created DESC
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
					JOIN post.favourites myFavourite
						WITH myFavourite.owner = :userId AND myFavourite.educationGroupHomeworkPost = post.id
					LEFT JOIN post.user user
					LEFT JOIN post.subject subject
					LEFT JOIN post.attachments attachments
					LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupHomeworkPost = post.id
					WHERE %ids_condition%
					GROUP BY post.id, attachments.id
					ORDER BY myFavourite.created DESC
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

					$r[0]->inMyFavorites = true;

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

		if ($type == 'EducationGroupSummaryPost') {

			$idsQuery =
				"
					SELECT
						DISTINCT post.id
					FROM InstudiesSiteBundle:EducationGroupSummaryPost post
					JOIN post.favourites myFavourite WITH myFavourite.owner = :userId
					GROUP BY post.id
					ORDER BY myFavourite.created DESC
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
								COUNT(comment.id)
							FROM InstudiesSiteBundle:Comment comment
							WHERE
								comment.educationGroupSummaryPost = post.id
						) as commentsCount,

						(
							SELECT
								COUNT(readedComment.id)
							FROM InstudiesSiteBundle:Comment readedComment
							JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
							WHERE
								readedComment.educationGroupSummaryPost = post.id
							GROUP BY post.id
						) as readedCommentCount,

						(
							SELECT
								COUNT(unreaded)
							FROM InstudiesSiteBundle:UnreadedEducationGroupSummaryPost unreaded
							WHERE
								unreaded.educationGroupSummaryPost = post.id
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

					FROM InstudiesSiteBundle:EducationGroupSummaryPost post
					JOIN post.favourites myFavourite
						WITH myFavourite.owner = :userId AND myFavourite.educationGroupSummaryPost = post.id
					LEFT JOIN post.user user
					LEFT JOIN post.subject subject
					LEFT JOIN post.attachments attachments
					LEFT JOIN post.activities creationActivity WITH creationActivity.educationGroupSummaryPost = post.id
					WHERE %ids_condition%
					GROUP BY post.id, attachments.id
					ORDER BY myFavourite.created DESC
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

					$r[0]->inMyFavorites = true;

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

		if ($type == 'User') {

			$idsQuery =
				"
					SELECT
						DISTINCT user.id
					FROM InstudiesSiteBundle:User user
					JOIN user.favourites myFavourite WITH myFavourite.owner = :userId
					GROUP BY user.id
					ORDER BY myFavourite.created DESC
				";

			$idsQueryParameters =
				array(
						'userId' => $userId
					);

			$itemsQuery =
				"
					SELECT
						user,
						count(DISTINCT myFavourite.id) as myFavouriteCount
					FROM InstudiesSiteBundle:User user
					LEFT JOIN user.favourites myFavourite WITH myFavourite.owner = :userId
					WHERE %ids_condition%
					GROUP BY user.id
					ORDER BY myFavourite.created DESC
				";

			$itemsQueryParameters =
				array(
						'userId' => $userId
					);

			$pagedQuery = new PagedQuery(
					$this->getEntityManager(),
					$page,
					$perPage,
					'user',
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
					$newResult[] = $r[0];
				}

			}

			return array(
					'users' => $newResult,
					'next' => $pagedQuery->getNext()
				);

		}

		if ($type == 'Comment') {

			$idsQuery =
				"
					SELECT
						DISTINCT comment.id
					FROM InstudiesSiteBundle:Comment comment
					JOIN comment.favourites myFavourite WITH myFavourite.owner = :userId
					GROUP BY comment.id
					ORDER BY myFavourite.created DESC
				";

			$idsQueryParameters =
				array(
						'userId' => $userId
					);

			$itemsQuery =
				"
					SELECT
						comment,
						educationGroup,
						educationGroupBlogPost,
						educationGroupEventPost,
						educationGroupHomeworkPost,
						educationGroupSummaryPost,
						attachment,
						user,
						count(DISTINCT myFavourite.id) as myFavouriteCount
					FROM
					InstudiesSiteBundle:Comment comment
					LEFT JOIN comment.user user
					LEFT JOIN comment.educationGroup educationGroup
					LEFT JOIN comment.educationGroupBlogPost educationGroupBlogPost
					LEFT JOIN comment.educationGroupEventPost educationGroupEventPost
					LEFT JOIN comment.educationGroupHomeworkPost educationGroupHomeworkPost
					LEFT JOIN comment.educationGroupSummaryPost educationGroupSummaryPost
					LEFT JOIN comment.attachments attachment
					LEFT JOIN comment.favourites myFavourite WITH myFavourite.owner = :userId
					WHERE %ids_condition%
					GROUP BY comment.id, attachment.id
					ORDER BY myFavourite.created DESC
				";

			$itemsQueryParameters =
				array(
						'userId' => $userId
					);

			$pagedQuery = new PagedQuery(
					$this->getEntityManager(),
					$page,
					$perPage,
					'comment',
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
					$newResult[] = $r[0];
				}

			}

			return array(
					'comments' => $newResult,
					'next' => $pagedQuery->getNext()
				);

		}

		if ($type == 'Message') {

			$idsQuery =
				"
					SELECT
						DISTINCT message.id
					FROM InstudiesSiteBundle:Message message
					JOIN message.favourites myFavourite WITH myFavourite.owner = :userId
					GROUP BY message.id
					ORDER BY myFavourite.created DESC
				";

			$idsQueryParameters =
				array(
						'userId' => $userId
					);

			$itemsQuery =
				"
					SELECT
						message,
						attachment,
						count(DISTINCT myFavourite.id) as myFavouriteCount
					FROM
					InstudiesSiteBundle:Message message
					LEFT JOIN message.attachments attachment
					LEFT JOIN message.favourites myFavourite WITH myFavourite.owner = :userId
					WHERE %ids_condition%
					GROUP BY message.id, attachment.id
					ORDER BY myFavourite.created DESC
				";

			$itemsQueryParameters =
				array(
						'userId' => $userId
					);

			$pagedQuery = new PagedQuery(
					$this->getEntityManager(),
					$page,
					$perPage,
					'message',
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
					if ($r[0]->getReciever()->getId() == $userId && !$r[0]->getReaded()) {
						$r[0]->isReaded = false;
					} else {
						$r[0]->isReaded = true;
					}
					$newResult[] = $r[0];
				}

			}

			return array(
					'messages' => $newResult,
					'next' => $pagedQuery->getNext()
				);

		}

	}

}