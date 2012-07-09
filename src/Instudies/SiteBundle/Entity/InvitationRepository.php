<?php

namespace Instudies\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * InvitationRepository
 */
class InvitationRepository extends EntityRepository
{

	public function findAllByEmailAndGroup($groupId, $email) {

		return $this->getEntityManager()
			->createQuery('
					SELECT i FROM
					InstudiesSiteBundle:Invitation i
					WHERE i.educationGroup = :groupId
					AND i.email = :email
				')
			->setParameter('groupId', $groupId)
			->setParameter('email', $email)
			->getOneOrNullResult();

	}

	public function findAllByUserActual($email) {

		return $this->getEntityManager()
			->createQuery('
					SELECT i FROM
					InstudiesSiteBundle:Invitation i
					WHERE (i.accepted = false OR i.accepted IS NULL) AND i.active = true
					AND i.email = :email
					ORDER BY i.created DESC
				')
			->setParameter('email', $email)
			->getResult();

	}

	public function findAllByGroupAccepted($groupId) {

		return $this->getEntityManager()
			->createQuery('
					SELECT i FROM
					InstudiesSiteBundle:Invitation i
					WHERE i.accepted = true
					AND i.educationGroup = :groupId
					ORDER BY i.created DESC
				')
			->setParameter('groupId', $groupId)
			->getResult();

	}

	public function findAllByGroupPending($groupId) {

		return $this->getEntityManager()
			->createQuery('
					SELECT i FROM
					InstudiesSiteBundle:Invitation i
					WHERE (i.accepted = false OR i.accepted IS NULL)
					AND i.active = true
					AND i.educationGroup = :groupId
					ORDER BY i.created DESC
				')
			->setParameter('groupId', $groupId)
			->getResult();

	}

}