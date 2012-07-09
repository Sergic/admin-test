<?php

namespace Instudies\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * JoinEducationGroupRequestRepository
 */
class JoinEducationGroupRequestRepository extends EntityRepository
{

    public function findAllByUserAndGroup ($userId, $groupId)
    {
        return $this->getEntityManager()
            ->createQuery('
                    SELECT j FROM
                    InstudiesSiteBundle:JoinEducationGroupRequest j
                    WHERE j.user = :user
                    AND j.educationGroup = :educationGroup
                ')
            ->setParameters(array(
                    'user' => $userId,
                    'educationGroup' => $groupId
                ))
            ->getOneOrNullResult();
    }

}