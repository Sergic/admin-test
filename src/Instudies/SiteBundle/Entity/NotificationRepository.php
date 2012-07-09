<?php

namespace Instudies\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * NotificationRepository
 */
class NotificationRepository extends EntityRepository
{

	public function findByUserGroupType($userId, $groupId, $type) {

		return $this->getEntityManager()
			->createQuery('
					SELECT n FROM
					InstudiesSiteBundle:Notification n
					WHERE n.educationGroup = :groupId
					AND n.reciever = :userId
					AND n.type = :type
				')
			->setParameter('userId', $userId)
			->setParameter('groupId', $groupId)
			->setParameter('type', $type)
			->getOneOrNullResult();

	}

	public function findByReciever($recieverId) {

		return $this->getEntityManager()
			->createQuery('
					SELECT n FROM
					InstudiesSiteBundle:Notification n
					WHERE n.reciever = :recieverId
					ORDER BY n.created DESC
				')
			->setParameter('recieverId', $recieverId)
			->getResult();

	}

}