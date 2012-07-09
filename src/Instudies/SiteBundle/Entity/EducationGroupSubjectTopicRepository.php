<?php

namespace Instudies\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EducationGroupSubjectTopicRepository
 */
class EducationGroupSubjectTopicRepository extends EntityRepository
{

	public function findBySubject ($subjectId, $userId)
	{

		$query = $this->getEntityManager()
					->createQuery("
						SELECT
							topic
						FROM InstudiesSiteBundle:EducationGroupSubjectTopic topic
						WHERE topic.subject = :subjectId
						GROUP BY topic.id
						ORDER BY topic.sort ASC, topic.date ASC
					");

		$result = $query
			->setParameter('subjectId', $subjectId)
			->getResult();

		$newResult = array();

		foreach ($result as $r) {
			$newResult[] = $r[0];
		}

		return $newResult;

	}

}