<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository,
	Instudies\SiteBundle\Helper\PagedQuery
;

/**
 * EducationGroupSubjectRepository
 */
class EducationGroupSubjectRepository extends EntityRepository
{

	public function count ($educationGroupId)
	{

		$date = new \DateTime();
		$date->sub(new \DateInterval('P1D'));

		$query = $this->getEntityManager()->createQueryBuilder()
					->select("count(subject.id) as subjectsCount")
					->from("InstudiesSiteBundle:EducationGroupSubject", "subject")
					->where("subject.educationGroup = :educationGroupId")
					->andWhere("(subject.archived = false OR subject.archived IS NULL)")
					->getQuery()
					->setParameter('educationGroupId', $educationGroupId);

		$actualSubjectsCount = $query->getSingleScalarResult();

		$query = $this->getEntityManager()->createQueryBuilder()
					->select("count(subject.id) as subjectsCount")
					->from("InstudiesSiteBundle:EducationGroupSubject", "subject")
					->where("subject.educationGroup = :educationGroupId")
					->andWhere("subject.archived = true")
					->getQuery()
					->setParameter('educationGroupId', $educationGroupId);

		$archivedSubjectsCount = $query->getSingleScalarResult();

		return array(
				'actualSubjectsCount' => $actualSubjectsCount,
				'archivedSubjectsCount' => $archivedSubjectsCount,
				'totalSubjectsCount' => $actualSubjectsCount + $archivedSubjectsCount
			);

	}

	public function findByEducationGroupWithSummariesCount ($educationGroupId)
	{

		$subjects = $this->getEntityManager()
					->createQuery("
						SELECT
							subject,
							(
								SELECT
									COUNT(actualPost.id)
								FROM InstudiesSiteBundle:EducationGroupSummaryPost actualPost
								WHERE
									(actualPost.deleted = false or actualPost.deleted IS NULL)
									AND
									actualPost.subject = subject.id
									AND
									(subject.archived = false OR subject.archived IS NULL)
							) as actualPostsCount,
							(
								SELECT
									COUNT(archivedPost.id)
								FROM InstudiesSiteBundle:EducationGroupSummaryPost archivedPost
								WHERE
									(archivedPost.deleted = false or archivedPost.deleted IS NULL)
									AND
									archivedPost.subject = subject.id
									AND
									subject.archived = true
							) as archivedPostsCount
						FROM InstudiesSiteBundle:EducationGroupSubject subject
						WHERE subject.educationGroup = :educationGroupId
						GROUP BY subject.id
						ORDER BY subject.title ASC
					")
					->setParameter('educationGroupId', $educationGroupId)
					->getResult();

		$return = array('actual' => array(), 'archived' => array());

		foreach ($subjects as $subject) {
			$subject[0]->actualPostsCount = $subject['actualPostsCount'];
			$subject[0]->archivedPostsCount = $subject['archivedPostsCount'];
			if ($subject[0]->getArchived()) {
				$return['archived'][] = $subject[0];
			} else {
				$return['actual'][] = $subject[0];
				if ($subject['archivedPostsCount'] > 0) {
					$return['archived'][] = $subject[0];
				}
			}
		}

		return $return;

	}

	public function findByEducationGroupWithHomeworksCount ($educationGroupId)
	{
		
		$date = new \DateTime();
		$date->sub(new \DateInterval('P1D'));

		$subjects = $this->getEntityManager()
					->createQuery("
						SELECT
							subject,
							(
								SELECT
									COUNT(actualPost.id)
								FROM InstudiesSiteBundle:EducationGroupHomeworkPost actualPost
								WHERE
									(actualPost.deleted = false or actualPost.deleted IS NULL)
									AND
									actualPost.subject = subject.id
									AND
									(subject.archived = false OR subject.archived IS NULL)
									AND
									actualPost.date >= :date
							) as actualPostsCount,
							(
								SELECT
									COUNT(archivedPost.id)
								FROM InstudiesSiteBundle:EducationGroupHomeworkPost archivedPost
								WHERE
									(archivedPost.deleted = false or archivedPost.deleted IS NULL)
									AND
									archivedPost.subject = subject.id
									AND
									(subject.archived = true OR archivedPost.date < :date)
							) as archivedPostsCount
						FROM InstudiesSiteBundle:EducationGroupSubject subject
						WHERE subject.educationGroup = :educationGroupId
						GROUP BY subject.id
						ORDER BY subject.title ASC
					")
					->setParameter('educationGroupId', $educationGroupId)
					->setParameter('date', $date)
					->getResult();

		$return = array('actual' => array(), 'archived' => array());

		foreach ($subjects as $subject) {
			$subject[0]->actualPostsCount = $subject['actualPostsCount'];
			$subject[0]->archivedPostsCount = $subject['archivedPostsCount'];
			if ($subject[0]->getArchived()) {
				$return['archived'][] = $subject[0];
			} else {
				$return['actual'][] = $subject[0];
				if ($subject['archivedPostsCount'] > 0) {
					$return['archived'][] = $subject[0];
				}
			}
		}

		return $return;

	}

	public function findOneByIdFull ($subjectId, $userId)
	{

		$query = $this->getEntityManager()
					->createQuery("
						SELECT
							subject,
							teachers,
							count(subjectTopic.id) as subjectTopicCount
						FROM InstudiesSiteBundle:EducationGroupSubject subject
						LEFT JOIN subject.teachers teachers
						LEFT JOIN subject.topics subjectTopic WITH subjectTopic.subject = subject.id
						WHERE subject.id = :subjectId
						GROUP BY subject.id, teachers.id
					");

		$result = $query
			->setParameter('subjectId', $subjectId)
			->getOneOrNullResult();

		if (count($result[0]) > 0) {

			$result[0]->topicsNum = $result['subjectTopicCount'];

			return $result[0];

		}

		return false;

	}

	public function findByEducationGroup ($educationGroupId, $userId, $filter = null, $page = 1, $perPage = 10)
	{

		if ($page == 'none') {
			$page = 1;
			$perPage = 10000;
			$return = 'array';
		} else {
			$return = false;
		}

		if ($filter == "actual") {
			$condition = "AND (subject.archived = false OR subject.archived IS NULL)";
		} elseif ($filter == "archived") {
			$condition = "AND subject.archived = true";
		} else {
			$condition = "";
		}

		$idsQuery =
			"
				SELECT
					DISTINCT subject.id
				FROM InstudiesSiteBundle:EducationGroupSubject subject
				WHERE subject.educationGroup = :educationGroupId
				".$condition."
				GROUP BY subject.id
				ORDER BY subject.title ASC
			";

		$idsQueryParameters =
			array(
					'educationGroupId' => $educationGroupId
				);

		$itemsQuery =
			"
				SELECT
					subject,
					teachers,
					count(subjectTopic.id) as subjectTopicCount
				FROM InstudiesSiteBundle:EducationGroupSubject subject
				LEFT JOIN subject.teachers teachers
				LEFT JOIN subject.topics subjectTopic WITH subjectTopic.subject = subject.id
				WHERE %ids_condition%
				GROUP BY subject.id, teachers.id
				ORDER BY subject.title ASC
			";

		$itemsQueryParameters =
			array(
				);

		$pagedQuery = new PagedQuery(
				$this->getEntityManager(),
				$page,
				$perPage,
				'subject',
				$idsQuery,
				$idsQueryParameters,
				$itemsQuery,
				$itemsQueryParameters
			);

		$result = $pagedQuery->getResult();

		$newResult = array();

		if ($result) {

			foreach ($result as $r) {
				$r[0]->topicsNum = $r['subjectTopicCount'];
				$newResult[] = $r[0];
			}

		}

		if ($return == 'array') {

			return $newResult;

		}

		return array(
				'subjects' => $newResult,
				'next' => $pagedQuery->getNext()
			);

	}

	public function creationActivity ($subject)
	{
		return $this->getEntityManager()
            ->createQuery('
                    SELECT a FROM
                    InstudiesSiteBundle:Activity a
                    WHERE a.type = :type
                    AND a.educationGroupSubject = :subjectId
                ')
            ->setParameter('subjectId', $subject->getId())
            ->setParameter('type', "EDUCATION_GROUP_NEW_SUBJECT")
            ->getOneOrNullResult();

	}

}