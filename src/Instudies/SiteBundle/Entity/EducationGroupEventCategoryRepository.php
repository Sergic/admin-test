<?php

namespace Instudies\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EducationGroupEventCategoryRepository
 */
class EducationGroupEventCategoryRepository extends EntityRepository
{

	public function findByEducationGroupWithEventsCount ($educationGroupId)
	{

		$date = new \DateTime();
		$date->sub(new \DateInterval('P1D'));

		$categories = $this->getEntityManager()
					->createQuery("
						SELECT
							category,
							(
								SELECT
									COUNT(actualPost.id)
								FROM InstudiesSiteBundle:EducationGroupEventPost actualPost
								WHERE
									actualPost.educationGroup = :educationGroupId
									AND
									(actualPost.deleted = false or actualPost.deleted IS NULL)
									AND
									actualPost.category = category.id
									AND
									actualPost.date >= :date
							) as actualPostsCount,
							(
								SELECT
									COUNT(archivedPost.id)
								FROM InstudiesSiteBundle:EducationGroupEventPost archivedPost
								WHERE
									archivedPost.educationGroup = :educationGroupId
									AND
									(archivedPost.deleted = false or archivedPost.deleted IS NULL)
									AND
									archivedPost.category = category.id
									AND
									archivedPost.date < :date
							) as archivedPostsCount
						FROM InstudiesSiteBundle:EducationGroupEventCategory category
						GROUP BY category.id
						ORDER BY category.title ASC
					")
					->setParameter('educationGroupId', $educationGroupId)
					->setParameter('date', $date)
					->getResult();

		$return = array('actual' => array(), 'archived' => array());

		foreach ($categories as $category) {
			$category[0]->actualPostsCount = $category['actualPostsCount'];
			$category[0]->archivedPostsCount = $category['archivedPostsCount'];
			$return['actual'][] = $category[0];
			if ($category['archivedPostsCount'] > 0) {
				$return['archived'][] = $category[0];
			}
		}

		return $return;

		$date = new \DateTime();
		$date->sub(new \DateInterval('P1D'));

		$query = $this->getEntityManager()
			->createQuery("
				SELECT
					category, count(post.id) as actualPostsCount
				FROM InstudiesSiteBundle:EducationGroupEventCategory category
				LEFT JOIN category.eventPosts post WITH post.educationGroup = :groupId AND post.date >= :date
				GROUP BY category.id
				ORDER BY category.title ASC
			")
			->setParameter('groupId', $groupId)
			->setParameter('date', $date);

		$result = $query->getResult();

		$newResult = array();

		foreach ($result as $r) {

			$r[0]->actualEventPostsCount = $r['actualPostsCount'];
			$newResult[] = $r[0];

		}

		return $newResult;

	}

}