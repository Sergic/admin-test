<?php

namespace Instudies\SiteBundle\Entity;

use
	Doctrine\ORM\EntityRepository
;

use
    Instudies\SiteBundle\Entity\ReadedComment
;

/**
 * ReadedCommentRepository
 */
class ReadedCommentRepository extends EntityRepository
{

	public function read ($user, $item)
	{

		if (count($item) > 0 && count($user) > 0) {

			$readed = new ReadedComment();
			$readed->setUser($user);
			$readed->setComment($item);

			$this->getEntityManager()->persist($readed);
			$this->getEntityManager()->flush();

		}

	}

}