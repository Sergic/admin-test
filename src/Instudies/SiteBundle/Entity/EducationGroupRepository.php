<?php

namespace Instudies\SiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EducationGroupRepository
 */
class EducationGroupRepository extends EntityRepository
{

    public function totalCounters ($educationGroupId)
    {
        return $this->getEntityManager()
            ->createQuery("
                SELECT

                    (
                        SELECT
                            COUNT(email)
                        FROM
                            InstudiesSiteBundle:EducationGroupEmailMessage email
                        WHERE
                            email.educationGroup = :educationGroupId
                            AND
                            (email.deleted = false or email.deleted IS NULL)
                    ) as emailCount,

                    (
                        SELECT
                            COUNT(homework)
                        FROM
                            InstudiesSiteBundle:EducationGroupHomeworkPost homework
                        WHERE
                            homework.educationGroup = :educationGroupId
                            AND
                            (homework.deleted = false or homework.deleted IS NULL)
                    ) as homeworkCount,

                    (
                        SELECT
                            COUNT(summary)
                        FROM
                            InstudiesSiteBundle:EducationGroupSummaryPost summary
                        WHERE
                            summary.educationGroup = :educationGroupId
                            AND
                            (summary.deleted = false or summary.deleted IS NULL)
                    ) as summaryCount,

                    (
                        SELECT
                            COUNT(blog)
                        FROM
                            InstudiesSiteBundle:EducationGroupBlogPost blog
                        WHERE
                            blog.educationGroup = :educationGroupId
                            AND
                            (blog.deleted = false or blog.deleted IS NULL)
                    ) as blogCount,

                    (
                        SELECT
                            COUNT(event)
                        FROM
                            InstudiesSiteBundle:EducationGroupEventPost event
                        WHERE
                            event.educationGroup = :educationGroupId
                            AND
                            (event.deleted = false or event.deleted IS NULL)
                    ) as eventCount,

                    (
                        SELECT
                            COUNT(subject)
                        FROM
                            InstudiesSiteBundle:EducationGroupSubject subject
                        WHERE
                            subject.educationGroup = :educationGroupId
                    ) as subjectCount

                FROM
                    InstudiesSiteBundle:EducationGroup educationGroup
                WHERE
                    educationGroup.id = :educationGroupId
                GROUP BY educationGroup.id
            ")
            ->setParameter('educationGroupId', $educationGroupId)
            ->getOneOrNullResult();

    }

    public function counters ($educationGroupId, $userId)
    {

        $date = new \DateTime();
        $date->sub(new \DateInterval('P1D'));

        $chatDate = new \DateTime();
        $chatDate->sub(new \DateInterval('PT120S'));

        return $this->getEntityManager()
            ->createQuery("
                SELECT

                    (
                        SELECT
                            COUNT(emailUnreaded)
                        FROM
                            InstudiesSiteBundle:EducationGroupEmailMessage email
                        JOIN
                            email.unreaded emailUnreaded
                        WITH
                            emailUnreaded.educationGroupEmailMessage = email.id
                            AND
                            emailUnreaded.user = :userId
                        WHERE
                            email.educationGroup = :educationGroupId
                    ) as emailUnreadedCount,

                    (
                        SELECT
                            COUNT(blogUnreadedUnreaded)
                        FROM
                            InstudiesSiteBundle:EducationGroupBlogPost blogUnreaded
                        JOIN
                            blogUnreaded.unreaded blogUnreadedUnreaded
                        WITH
                            blogUnreadedUnreaded.educationGroupBlogPost = blogUnreaded.id
                            AND
                            blogUnreadedUnreaded.user = :userId
                        WHERE
                            blogUnreaded.educationGroupAssociated = true
                            AND
                            blogUnreaded.educationGroup = :educationGroupId
                    ) as blogUnreadedCount,

                    (
                        SELECT
                            COUNT(blogReaded) - COUNT(blogReadedReaded)
                        FROM
                            InstudiesSiteBundle:EducationGroupBlogPost blogReaded
                        LEFT JOIN
                            blogReaded.readed blogReadedReaded
                        WITH
                            blogReadedReaded.educationGroupBlogPost = blogReaded.id
                            AND
                            blogReadedReaded.user = :userId
                        WHERE
                            blogReaded.educationGroupAssociated = false
                            AND
                            (blogReaded.deleted IS NULL OR blogReaded.deleted = false)
                    ) as blogUnreaded2Count,

                    (
                        SELECT
                            COUNT(eventUnreaded)
                        FROM
                            InstudiesSiteBundle:EducationGroupEventPost event
                        JOIN
                            event.unreaded eventUnreaded
                        WITH
                            eventUnreaded.educationGroupEventPost = event.id
                            AND
                            eventUnreaded.user = :userId
                        WHERE
                            event.educationGroup = :educationGroupId
                            AND
                            (event.deleted IS NULL OR event.deleted = false)
                            AND
                            event.date >= :date
                    ) as eventUnreadedCount,

                    (
                        SELECT
                            COUNT(homeworkUnreaded)
                        FROM
                            InstudiesSiteBundle:EducationGroupHomeworkPost homework
                        JOIN
                            homework.subject homeworkSubject
                            WITH
                                homeworkSubject.archived IS NULL OR homeworkSubject.archived = false
                        JOIN
                            homework.unreaded homeworkUnreaded
                            WITH
                                homeworkUnreaded.educationGroupHomeworkPost = homework.id
                                AND
                                homeworkUnreaded.user = :userId
                        WHERE
                            homework.educationGroup = :educationGroupId
                            AND
                            (homework.deleted IS NULL OR homework.deleted = false)
                            AND
                            homework.date >= :date
                    ) as homeworkUnreadedCount,


                    (
                        SELECT
                            COUNT(summaryUnreaded)
                        FROM
                            InstudiesSiteBundle:EducationGroupSummaryPost summary
                        JOIN
                            summary.subject summarySubject
                        WITH
                            summarySubject.archived IS NULL OR summarySubject.archived = false
                        JOIN
                            summary.unreaded summaryUnreaded
                        WITH
                            summaryUnreaded.educationGroupSummaryPost = summary.id
                            AND
                            summaryUnreaded.user = :userId
                        WHERE
                            summary.educationGroup = :educationGroupId
                            AND
                            (summary.deleted IS NULL OR summary.deleted = false)
                    ) as summaryUnreadedCount,

                    (
                        SELECT
                            COUNT(userGroup.id)
                        FROM
                            InstudiesSiteBundle:UserEducationGroup userGroup
                        WHERE
                            userGroup.lastChatActivity > :chatDate
                            AND
                            userGroup.educationGroup = :educationGroupId
                    ) as userOnlineInChatCount

                FROM
                    InstudiesSiteBundle:EducationGroup educationGroup
                WHERE
                    educationGroup.id = :educationGroupId
                GROUP BY educationGroup.id
            ")
            ->setParameter('educationGroupId', $educationGroupId)
            ->setParameter('userId', $userId)
            ->setParameter('chatDate', $chatDate)
            ->setParameter('date', $date)
            ->getOneOrNullResult();

    }

    public function check ($groupSlug, $userId)
    {

        $query = $this->getEntityManager()->createQueryBuilder()
                    ->select("userEducationGroup", "educationGroup", "groupUsers", "user")
                    ->from("InstudiesSiteBundle:UserEducationGroup", "userEducationGroup")
                    ->leftJoin("userEducationGroup.educationGroup","educationGroup")
                    ->leftJoin("educationGroup.users","groupUsers")
                    ->leftJoin("groupUsers.user","user")
                    ->where("educationGroup.slug = :groupSlug")
                    ->andWhere("userEducationGroup.user = :userId")
                    ->setParameter("groupSlug", $groupSlug)
                    ->setParameter("userId", $userId)
                    ->getQuery();

        return $query->getOneOrNullResult();
   
    }

    public function findOne ($userId, $educationGroupId)
    {
        return $this->getEntityManager()
            ->createQuery('
                    SELECT u, g FROM
                    InstudiesSiteBundle:UserEducationGroup u
                    LEFT JOIN u.educationGroup g
                    WHERE u.user = :user
                    AND u.educationGroup = :educationGroupId
                ')
            ->setParameters(array(
                    'user' => $userId,
                    'educationGroupId' => $educationGroupId
                ))
            ->getOneOrNullResult();
    }

    public function countAllGroups(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->add('select', $qb->expr()->count('educationGroup.id').'  as count_group');
        $qb->from("InstudiesSiteBundle:EducationGroup", "educationGroup");
        $qb->where($qb->expr()->eq('educationGroup.deleted', '0'));
        $q = $qb->getQuery();
        return $q->getSingleScalarResult();
    }

    public function totalActiveGroups(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('userEducationGroup.id');
        $qb->addSelect('educationGroup.id');
        $qb->addSelect($qb->expr()->count('user.id').' as count_user');
        $qb->from("InstudiesSiteBundle:UserEducationGroup", 'userEducationGroup');
        $qb->leftJoin('userEducationGroup.user', 'user');
        $qb->innerJoin('userEducationGroup.educationGroup', 'educationGroup');
        $qb->having($qb->expr()->gte('count_user', '4'));
        $qb->groupBy('educationGroup.id');
        $q = $qb->getQuery();
        return count($q->getScalarResult());
    }

    public function getActiveGroupsIds(){
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('userEducationGroup.id');
        $qb->addSelect('educationGroup.id as group_id');
        $qb->addSelect($qb->expr()->count('user.id').' as count_user');
        $qb->from("InstudiesSiteBundle:UserEducationGroup", 'userEducationGroup');
        $qb->leftJoin('userEducationGroup.user', 'user');
        $qb->innerJoin('userEducationGroup.educationGroup', 'educationGroup');
        $qb->having($qb->expr()->gte('count_user', '4'));
        $qb->groupBy('educationGroup.id');
        $q = $qb->getQuery();
        $ids = array();
        foreach($q->getScalarResult() as $group){
            $ids[] = $group['group_id'];
        }
        return $ids;
    }
}