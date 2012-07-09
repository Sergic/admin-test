<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository,
	Instudies\SiteBundle\Helper\PagedQuery
;

use
    Instudies\SiteBundle\Entity\ReadedActivity
;

/**
 * ActivityRepository
 */
class ActivityRepository extends EntityRepository
{

	public function read ($user, $item)
	{

		if (count($item) > 0 && count($user) > 0) {

			if ($item->getEducationGroupAssociated()) {

				$unreaded = $this->getEntityManager()->getRepository('InstudiesSiteBundle:UnreadedActivity')->findBy(array('user' => $user->getId(), 'activity' => $item->getId()));

				foreach ($unreaded as $u) {
					$this->getEntityManager()->remove($u);
				}

			} else {

				$readed = new ReadedActivity();
				$readed->setUser($user);
				$readed->setActivity($item);

				$this->getEntityManager()->persist($readed);

			}

			$this->getEntityManager()->flush();

		}

	}

	public function findByEducationGroupAndLastActivityId ($educationGroup, $userId, $lastActivityId) {

		$query = $this->getEntityManager()
			->createQuery('
					SELECT
						activity,
						(
							SELECT
								COUNT(unreaded)
							FROM
								InstudiesSiteBundle:UnreadedActivity unreaded
							WHERE
								activity.educationGroupAssociated = true
								AND
								unreaded.activity = activity.id
								AND
								unreaded.user = :userId
						) as unreadedCount,
						(
							SELECT
								COUNT(readed)
							FROM
								InstudiesSiteBundle:ReadedActivity readed
							WHERE
								activity.educationGroupAssociated = false
								AND
								readed.activity = activity.id
								AND
								readed.user = :userId
						) as readedCount,
						user,
						educationGroup,
						educationGroupBlogPost,
						educationGroupEventPost,
						educationGroupHomeworkPost,
						educationGroupSummaryPost,
						educationGroupSubject,
						educationGroupSubjectTopic,
						comment,
						commentEducationGroupBlogPost,
						commentEducationGroupEventPost,
						commentEducationGroupHomeworkPost,
						commentEducationGroupSummaryPost
					FROM InstudiesSiteBundle:Activity activity
					LEFT JOIN activity.user user
					LEFT JOIN activity.educationGroup educationGroup
					LEFT JOIN activity.educationGroupBlogPost educationGroupBlogPost
					LEFT JOIN activity.educationGroupEventPost educationGroupEventPost
					LEFT JOIN activity.educationGroupHomeworkPost educationGroupHomeworkPost
					LEFT JOIN activity.educationGroupSummaryPost educationGroupSummaryPost
					LEFT JOIN activity.educationGroupSubject educationGroupSubject
					LEFT JOIN activity.educationGroupSubjectTopic educationGroupSubjectTopic
					LEFT JOIN activity.comment comment
					LEFT JOIN comment.educationGroupBlogPost commentEducationGroupBlogPost
					LEFT JOIN comment.educationGroupEventPost commentEducationGroupEventPost
					LEFT JOIN comment.educationGroupHomeworkPost commentEducationGroupHomeworkPost
					LEFT JOIN comment.educationGroupSummaryPost commentEducationGroupSummaryPost
					WHERE
						(activity.educationGroup = :educationGroupId OR activity.educationGroupAssociated = false)
						AND
						activity.created >= :date
						AND
						activity.id > :lastActivityId
					GROUP BY activity.id
					ORDER BY activity.id DESC
				')
			->setParameter('educationGroupId', $educationGroup->getId())
			->setParameter('date', $educationGroup->getCreated())
			->setParameter('userId', $userId)
			->setParameter('lastActivityId', $lastActivityId);

		$result = $query
					->getResult();

		$newResult = array();

		foreach ($result as $r) {

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

			$newResult[] = $r[0];
		}

		return array(
				'activities' => $newResult
			);

	}

	public function findByEducationGroup ($educationGroup, $userId, $page = 1, $perPage = 10) {

		$idsQuery =
			"
				SELECT
					DISTINCT activity.id
				FROM InstudiesSiteBundle:Activity activity
				WHERE
					(activity.educationGroup = :educationGroupId OR activity.educationGroupAssociated = false)
					AND
					activity.created >= :date
				GROUP BY activity.id
				ORDER BY activity.id DESC
			";

		$idsQueryParameters =
			array(
					'educationGroupId' => $educationGroup->getId(),
					'date' => $educationGroup->getCreated()
				);

		$itemsQuery =
			"
				SELECT
					activity,
					(
						SELECT
							COUNT(unreaded)
						FROM
							InstudiesSiteBundle:UnreadedActivity unreaded
						WHERE
							activity.educationGroupAssociated = true
							AND
							unreaded.activity = activity.id
							AND
							unreaded.user = :userId
					) as unreadedCount,
					(
						SELECT
							COUNT(readed)
						FROM
							InstudiesSiteBundle:ReadedActivity readed
						WHERE
							activity.educationGroupAssociated = false
							AND
							readed.activity = activity.id
							AND
							readed.user = :userId
					) as readedCount,
					user,
					educationGroup,
					educationGroupBlogPost,
					educationGroupEventPost,
					educationGroupHomeworkPost,
					educationGroupSummaryPost,
					educationGroupSubject,
					educationGroupSubjectTopic,
					comment,
					commentEducationGroupBlogPost,
					commentEducationGroupEventPost,
					commentEducationGroupHomeworkPost,
					commentEducationGroupSummaryPost
				FROM InstudiesSiteBundle:Activity activity
				LEFT JOIN activity.user user
				LEFT JOIN activity.educationGroup educationGroup
				LEFT JOIN activity.educationGroupBlogPost educationGroupBlogPost
				LEFT JOIN activity.educationGroupEventPost educationGroupEventPost
				LEFT JOIN activity.educationGroupHomeworkPost educationGroupHomeworkPost
				LEFT JOIN activity.educationGroupSummaryPost educationGroupSummaryPost
				LEFT JOIN activity.educationGroupSubject educationGroupSubject
				LEFT JOIN activity.educationGroupSubjectTopic educationGroupSubjectTopic
				LEFT JOIN activity.comment comment
				LEFT JOIN comment.educationGroupBlogPost commentEducationGroupBlogPost
				LEFT JOIN comment.educationGroupEventPost commentEducationGroupEventPost
				LEFT JOIN comment.educationGroupHomeworkPost commentEducationGroupHomeworkPost
				LEFT JOIN comment.educationGroupSummaryPost commentEducationGroupSummaryPost
				WHERE %ids_condition%
				GROUP BY activity.id
				ORDER BY activity.id DESC
			";

		$itemsQueryParameters =
			array(
					'userId' => $userId
				);

		$pagedQuery = new PagedQuery(
				$this->getEntityManager(),
				$page,
				$perPage,
				'activity',
				$idsQuery,
				$idsQueryParameters,
				$itemsQuery,
				$itemsQueryParameters
			);

		$result = $pagedQuery->getResult();

		$newResult = array();

		if ($result) {

			foreach ($result as $r) {

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

				$newResult[] = $r[0];

			}

		}

		return array(
				'activities' => $newResult,
				'next' => $pagedQuery->getNext()
			);

	}

}