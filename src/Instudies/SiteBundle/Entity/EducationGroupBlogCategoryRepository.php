<?php

namespace Instudies\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EducationGroupBlogCategoryRepository
 */
class EducationGroupBlogCategoryRepository extends EntityRepository
{

	public function findByEducationGroupWithBlogsCount ($groupId)
	{

		$date = new \DateTime();
		$date->sub(new \DateInterval('P1D'));

		$query = $this->getEntityManager()
					->createQuery("
						SELECT
							category,
							count(post.id) as postsCount
						FROM InstudiesSiteBundle:EducationGroupBlogCategory category
						LEFT JOIN category.blogPosts post WITH ((category.educationGroupAssociated = false AND post.educationGroupAssociated = false) OR (category.educationGroupAssociated = true AND post.educationGroup = :groupId)) AND (post.deleted = false OR post.deleted IS NULL)
						GROUP BY category.id
						ORDER BY category.educationGroupAssociated DESC, category.title ASC
					")
					->setParameter('groupId', $groupId);

		$result = $query->getResult();

		$newResult = array();

		foreach ($result as $r) {

			$r[0]->blogPostsCount = $r['postsCount'];
			$newResult[] = $r[0];

		}

		return $newResult;

	}

}