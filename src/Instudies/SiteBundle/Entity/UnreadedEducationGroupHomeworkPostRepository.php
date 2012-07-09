<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository
;

use
    Instudies\SiteBundle\Entity\UnreadedEducationGroupHomeworkPost,
    Instudies\SiteBundle\Helper\EmailNotifier
;

/**
 * UnreadedEducationGroupHomeworkPostRepository
 */
class UnreadedEducationGroupHomeworkPostRepository extends EntityRepository
{

	public function send ($item, $container)
	{

        $emailNotifier = new EmailNotifier($this->getEntityManager(), $container);

		$this->getEntityManager()->flush();

		foreach ($item->getEducationGroup()->getUsers() as $user) {

			$unreaded = new UnreadedEducationGroupHomeworkPost();
			$unreaded->setUser($user->getUser());
			$unreaded->setEducationGroupHomeworkPost($item);

			$this->getEntityManager()->persist($unreaded);

			if ($unreaded->getUser()->getLastVisit()->getTimestamp()+600 < date('U')) {
                $emailNotifier->homework($unreaded);
			}

		}

		$this->getEntityManager()->flush();

	}

	public function read ($user, $item)
	{

		if (count($item) > 0 && count($user) > 0) {

			$unreaded = $this->findBy(array('user' => $user->getId(), 'educationGroupHomeworkPost' => $item->getId()));

			foreach ($unreaded as $u) {
				$this->getEntityManager()->remove($u);
			}

			$this->getEntityManager()->flush();

		}

	}

}