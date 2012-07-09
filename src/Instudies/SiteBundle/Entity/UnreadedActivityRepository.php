<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository
;

use
    Instudies\SiteBundle\Entity\UnreadedActivity,
    Instudies\SiteBundle\Helper\EmailNotifier
;

/**
 * UnreadedActivityRepository
 */
class UnreadedActivityRepository extends EntityRepository
{

	public function send ($item, $container)
	{

		$this->getEntityManager()->flush();

		foreach ($item->getEducationGroup()->getUsers() as $user) {

			$unreaded = new UnreadedActivity();
			$unreaded->setUser($user->getUser());
			$unreaded->setActivity($item);

			$this->getEntityManager()->persist($unreaded);

		}

		$this->getEntityManager()->flush();

	}

}