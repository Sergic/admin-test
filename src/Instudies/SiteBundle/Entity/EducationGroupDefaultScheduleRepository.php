<?php

namespace Instudies\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EducationGroupDefaultScheduleRepository
 */
class EducationGroupDefaultScheduleRepository extends EntityRepository
{

    public function findByEducationGroup ($educationGroupId)
    {

        $result = $this->getEntityManager()
                ->createQuery("
                    SELECT
                        schedule
                    FROM InstudiesSiteBundle:EducationGroupDefaultSchedule schedule
                    WHERE
                        schedule.educationGroup = :educationGroupId
                    ORDER BY schedule.time ASC, schedule.endTime ASC
                ")
                ->setParameter('educationGroupId', $educationGroupId)
                ->getResult();

        return $result;

    }

}