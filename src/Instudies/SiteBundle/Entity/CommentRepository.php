<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository,
	Instudies\SiteBundle\Helper\PagedQuery
;

/**
 * CommentRepository
 */
class CommentRepository extends EntityRepository
{

	public function findByType ($contentId, $type, $userId)
	{

		if ($type == "EducationGroupEmailMessage") {
			$condition = "WHERE comment.educationGroupEmailMessage = :contentId";
		} elseif ($type == "EducationGroupBlogPost") {
			$condition = "WHERE comment.educationGroupBlogPost = :contentId";
		} elseif ($type == "EducationGroupEventPost") {
			$condition = "WHERE comment.educationGroupEventPost = :contentId";
		} elseif ($type == "EducationGroupHomeworkPost") {
			$condition = "WHERE comment.educationGroupHomeworkPost = :contentId";
		} elseif ($type == "EducationGroupSummaryPost") {
			$condition = "WHERE comment.educationGroupSummaryPost = :contentId";
		} elseif ($type == "Feedback") {
			$condition = "WHERE comment.feedback = :contentId";
		}

		$query = $this->getEntityManager()
				->createQuery("
					SELECT
						comment,
						attachment,
						user,
						count(DISTINCT myFavourite.id) as myFavouriteCount,
						creationActivity,
						count(DISTINCT readedComment) as readedCommentCount,
						count(DISTINCT creationActivityUnreaded) as creationActivityUnreadedCount
					FROM
					InstudiesSiteBundle:Comment comment
					LEFT JOIN comment.user user
					LEFT JOIN comment.attachments attachment
					LEFT JOIN comment.favourites myFavourite WITH myFavourite.owner = :userId
					LEFT JOIN comment.readedComment readedComment WITH readedComment.comment = comment.id AND readedComment.user = :userId
					LEFT JOIN comment.activities creationActivity WITH creationActivity.comment = comment.id
					LEFT JOIN creationActivity.unreaded creationActivityUnreaded WITH creationActivityUnreaded.activity = creationActivity.id AND creationActivityUnreaded.user = :userId
					".$condition."
					GROUP BY comment.id, attachment.id
					ORDER BY comment.created ASC
				");

		$result = $query
				->setParameter('contentId', $contentId)
				->setParameter('userId', $userId)
				->getResult();

		$newResult = array();

		foreach ($result as $r) {
			if ($r['myFavouriteCount'] > 0) {
				$r[0]->inMyFavorites = true;
			} else {
				$r[0]->inMyFavorites = false;
			}

			if ($r['readedCommentCount'] > 0) {
				$r[0]->isReaded = true;
			} else {
				$r[0]->isReaded = false;
			}
			if ($r['creationActivityUnreadedCount'] > 0) {
				$r[0]->creationActivityIsReaded = false;
			} else {
				$r[0]->creationActivityIsReaded = true;
			}
			$newResult[] = $r[0];
		}

		return $newResult;

	}

	public function creationActivity ($comment)
	{
		return $this->getEntityManager()
            ->createQuery('
                    SELECT a FROM
                    InstudiesSiteBundle:Activity a
                    WHERE a.type = :type
                    AND a.comment = :commentId
                ')
            ->setParameter('commentId', $comment->getId())
            ->setParameter('type', "EDUCATION_GROUP_NEW_COMMENT")
            ->getOneOrNullResult();

	}

	public function commentsReadedNum ($user, $comments)
	{

		$counter = 0;

		if (count($user) > 0 && count($comments) > 0) {

			foreach ($comments as $comment) {

				$counter = $counter + $this->getEntityManager()
			        ->createQuery('
			                SELECT count(r.id) FROM
			                InstudiesSiteBundle:Readed r
			                WHERE r.user = :userId
			                AND r.comment = :commentId
			            ')
			        ->setParameters(array(
			                'userId' => $user->getId(),
			                'commentId' => $comment->getId()
			            ))
					->getSingleScalarResult();

			}

		}

		return $counter;

	}

	public function findByUser ($userId, $watcherId, $page = 1, $perPage = 10)
	{

		if ($userId != $watcherId) {

			$groupIds = $this->getEntityManager()->getRepository('InstudiesSiteBundle:User')->groupMateIds($userId, $watcherId);

			$groupIdsCondition = " AND ( ";

			$counter=0;
			foreach ($groupIds as $groupId) {
				$counter++;
				$groupIdsCondition .= " comment.educationGroup = " . $groupId['id'] . " ";
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
					DISTINCT comment.id
				FROM InstudiesSiteBundle:Comment comment
				WHERE comment.user = :userId ". $groupIdsCondition ."
				GROUP BY comment.id
				ORDER BY comment.created DESC
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
					count(DISTINCT readedComment) as readedCommentCount,
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
				LEFT JOIN comment.readedComment readedComment WITH readedComment.comment = comment.id AND readedComment.user = :userId
				WHERE %ids_condition%
				GROUP BY comment.id, attachment.id
				ORDER BY comment.created DESC
			";

		$itemsQueryParameters =
			array(
					'userId' => $watcherId
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
				if ($r['readedCommentCount'] > 0) {
					$r[0]->isReaded = true;
				} else {
					$r[0]->isReaded = false;
				}
				$newResult[] = $r[0];
			}

		}

		return array(
				'comments' => $newResult,
				'next' => $pagedQuery->getNext()
			);

	}

}