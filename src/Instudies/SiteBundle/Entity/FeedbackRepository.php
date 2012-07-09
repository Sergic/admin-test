<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository,
	Instudies\SiteBundle\Helper\PagedQuery
;

/**
 * FeedbackRepository
 */
class FeedbackRepository extends EntityRepository
{

	public function findOneByIdFull ($postId, $userId)
	{

		$result = $this->getEntityManager()
					->createQuery("
						SELECT
							post,
							user,
							attachments,

							(
								SELECT
									COUNT(myFavourite.id)
								FROM InstudiesSiteBundle:Favourite myFavourite
								WHERE
									myFavourite.owner = :userId
									AND
									myFavourite.feedback = post.id
							) as myFavouriteCount,

							(
								SELECT
									COUNT(comment.id)
								FROM InstudiesSiteBundle:Comment comment
								WHERE
									comment.feedback = post.id
							) as commentsCount,

							(
								SELECT
									COUNT(readedComment.id)
								FROM InstudiesSiteBundle:Comment readedComment
								JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
								WHERE
									readedComment.feedback = post.id
								GROUP BY post.id
							) as readedCommentCount

						FROM InstudiesSiteBundle:Feedback post
						LEFT JOIN post.user user
						LEFT JOIN post.attachments attachments
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

			$result[0]->commentsNum = $result['commentsCount'];

			$result[0]->commentsNewNum = $result[0]->commentsNum - $result['readedCommentCount'];

			return $result[0];

		}

		return false;

	}

	public function findByType ($userId, $type = null, $page = 1, $perPage = 10)
	{

		$idsQuery =
			"
				SELECT
					DISTINCT post.id
				FROM InstudiesSiteBundle:Feedback post
				WHERE post.type = :type
				GROUP BY post.id
				ORDER BY post.created DESC
			";

		$idsQueryParameters =
			array(
					'type' => $type
				);

		$itemsQuery =
			"
				SELECT
					post,
					user,
					attachments,

					(
						SELECT
							COUNT(myFavourite.id)
						FROM InstudiesSiteBundle:Favourite myFavourite
						WHERE
							myFavourite.owner = :userId
							AND
							myFavourite.feedback = post.id
					) as myFavouriteCount,

					(
						SELECT
							COUNT(comment.id)
						FROM InstudiesSiteBundle:Comment comment
						WHERE
							comment.feedback = post.id
					) as commentsCount,

					(
						SELECT
							COUNT(readedComment.id)
						FROM InstudiesSiteBundle:Comment readedComment
						JOIN readedComment.readedComment readedCommentReaded WITH readedCommentReaded.comment = readedComment.id AND readedCommentReaded.user = :userId
						WHERE
							readedComment.feedback = post.id
						GROUP BY post.id
					) as readedCommentCount

				FROM InstudiesSiteBundle:Feedback post
				LEFT JOIN post.user user
				LEFT JOIN post.attachments attachments
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

}