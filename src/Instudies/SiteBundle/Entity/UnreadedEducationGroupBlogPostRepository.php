<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository
;

use
    Instudies\SiteBundle\Entity\UnreadedEducationGroupBlogPost,
    Instudies\SiteBundle\Helper\EmailNotifier
;

/**
 * UnreadedEducationGroupBlogPostRepository
 */
class UnreadedEducationGroupBlogPostRepository extends EntityRepository
{

	public function send ($item, $container)
	{

        $emailNotifier = new EmailNotifier($this->getEntityManager(), $container);

		$this->getEntityManager()->flush();

		foreach ($item->getEducationGroup()->getUsers() as $user) {

			$unreaded = new UnreadedEducationGroupBlogPost();
			$unreaded->setUser($user->getUser());
			$unreaded->setEducationGroupBlogPost($item);

			$this->getEntityManager()->persist($unreaded);

			if ($unreaded->getUser()->getLastVisit()->getTimestamp()+600 < date('U')) {
                $emailNotifier->blog($unreaded);
			}

		}

		$this->getEntityManager()->flush();

	}

}