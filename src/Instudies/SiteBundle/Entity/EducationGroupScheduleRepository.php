<?php

namespace Instudies\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EducationGroupScheduleRepository
 */
class EducationGroupScheduleRepository extends EntityRepository
{


    public function findByEducationGroup ($educationGroupId, $date)
    {

        $result = $this->getEntityManager()
                ->createQuery("
                    SELECT
                        schedule,
                        subject
                    FROM InstudiesSiteBundle:EducationGroupSchedule schedule
                    JOIN schedule.subject subject
                        WITH subject.archived = false OR subject.archived IS NULL
                    WHERE
                        schedule.educationGroup = :educationGroupId
                    GROUP BY schedule.id, subject.id
                    HAVING
                        (
                            (schedule.date = :date)
                            OR
                            (schedule.repeat = 'every-week' AND schedule.weekDay = :weekDay)
                            OR
                            (
                                schedule.repeat = 'every-2-weeks' AND schedule.weekDay = :weekDay
                                AND
                                MOD(SUM(:weekNum - schedule.weekNum), 2) = 0
                            )
                            OR
                            (
                                schedule.repeat = 'every-3-weeks' AND schedule.weekDay = :weekDay
                                AND
                                MOD(SUM(:weekNum - schedule.weekNum), 3) = 0

                            )
                            OR
                            (
                                schedule.repeat = 'every-4-weeks' AND schedule.weekDay = :weekDay
                                AND
                                MOD(SUM(:weekNum - schedule.weekNum), 4) = 0

                            )
                        )
                    ORDER BY schedule.time ASC, schedule.endTime ASC
                ")
                ->setParameter('educationGroupId', $educationGroupId)
                ->setParameter('date', $date)
                ->setParameter('weekDay', date('w', $date->getTimestamp()))
                ->setParameter('weekNum', ceil($date->getTimestamp()  / 604800))
                ->getResult();

        return $result;

    }

	public function counters ($educationGroupId, $days, $month, $year)
    {

        $month = intval($month);
        $year = intval($year);

        $dateCondition = '';

        for ($i=1;$i<=$days;$i++) {
            $dateCondition .= " homework.date = :date".$i." ";
            if ($days != $i) {
                $dateCondition .= " OR ";
            }
        }

        $query = $this->getEntityManager()
                ->createQuery("
                    SELECT
                        homework.id as homeworkId,
                        homework.date as homeworkDate
                    FROM InstudiesSiteBundle:EducationGroup educationGroup
                    LEFT JOIN educationGroup.homeworkPosts homework
                    WHERE educationGroup.id = :educationGroupId
                    AND (". $dateCondition .")
                    GROUP BY homework.id
                ")
                ->setParameter('educationGroupId', $educationGroupId);

        for ($i=1;$i<=$days;$i++) {
            $query->setParameter('date'.$i,new \DateTime($i . '-' . $month . '-' . $year));
        }

        $homeworkResult = $query->getResult();

        $dateCondition = '';

        for ($i=1;$i<=$days;$i++) {
            $dateCondition .= " event.date = :date".$i." ";
            if ($days != $i) {
                $dateCondition .= " OR ";
            }
        }

        $query = $this->getEntityManager()
                ->createQuery(trim("
                    SELECT
                        event.id as eventId,
                        event.date as eventDate
                    FROM InstudiesSiteBundle:EducationGroup educationGroup
                    LEFT JOIN educationGroup.eventPosts event
                    WHERE educationGroup.id = :educationGroupId
                    AND (". $dateCondition .")
                    GROUP BY event.id
                "))
                ->setParameter('educationGroupId', $educationGroupId);

        for ($i=1;$i<=$days;$i++) {
            $query->setParameter('date'.$i,new \DateTime($i . '-' . $month . '-' . $year));
        }

        $eventResult = $query->getResult();

        $return = array();

        for ($i=1;$i<=$days;$i++) {
            $return[$i] = array('homeworks' => 0, 'events' => 0);
        }

        foreach ($homeworkResult as $h) {
            $date = $h['homeworkDate'];
            $day = date('d',$date->getTimestamp());
            $return[intval($day)]['homeworks']++;
        }

        foreach ($eventResult as $h) {
            $date = $h['eventDate'];
            $day = date('d',$date->getTimestamp());
            $return[intval($day)]['events']++;
        }

        return $return;

    }

}