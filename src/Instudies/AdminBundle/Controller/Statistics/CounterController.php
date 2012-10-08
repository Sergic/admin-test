<?php

namespace Instudies\AdminBundle\Controller\Statistics;

use
    Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Symfony\Component\HttpFoundation\Request
;

/**
 * @Route("/admin/statistics/counters")
 */
class CounterController extends Controller
{
    /**
     * @Route("/", name="admin_statistics_counter")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $groupRepository = $this->getDoctrine()->getEntityManager()->getRepository('InstudiesSiteBundle:EducationGroup');
        $count_groups = $groupRepository->countAllGroups();

        $total_active_groups = $groupRepository->totalActiveGroups();
        $active_groups_ids = $groupRepository->getActiveGroupsIds();

        $userRepository = $this->getDoctrine()->getEntityManager()->getRepository('InstudiesSiteBundle:User');
        $count_total_users = $userRepository->totalUndeletedUsers();

        $total_active_users = $userRepository->totalActiveUsers($active_groups_ids);

        $homeworkRepository = $this->getDoctrine()->getEntityManager()->getRepository('InstudiesSiteBundle:EducationGroupHomeworkPost');
        $count_homeworks = $homeworkRepository->totalUndeletedHomeworks();

        $summaryRepository = $this->getDoctrine()->getEntityManager()->getRepository('InstudiesSiteBundle:EducationGroupSummaryPost');
        $count_summary = $summaryRepository->totalUndeletedSummary();

        $eventRepository = $this->getDoctrine()->getEntityManager()->getRepository('InstudiesSiteBundle:EducationGroupEventPost');
        $count_events = $eventRepository->totalUndeletedEvents();

        $blogRepository = $this->getDoctrine()->getEntityManager()->getRepository('InstudiesSiteBundle:EducationGroupBlogPost');
        $count_blogs = $blogRepository->totalUndeletedBlogs();

        $commentRepository = $this->getDoctrine()->getEntityManager()->getRepository('InstudiesSiteBundle:Comment');
        $count_comments = $commentRepository->totalUndeletedComments();

        $messageRepository = $this->getDoctrine()->getEntityManager()->getRepository('InstudiesSiteBundle:Message');
        $count_messages = $messageRepository->totalUndeletedMessages();

        return array(
            'count_groups' => $count_groups,
            'total_active_groups' => $total_active_groups,
            'count_total_users' => $count_total_users,
            'total_active_users' => $total_active_users,
            'count_homeworks' => $count_homeworks,
            'count_summary' => $count_summary,
            'count_events' => $count_events,
            'count_blogs' => $count_blogs,
            'count_comments' => $count_comments,
            'count_messages' => $count_messages,
            'menu_active' => array(1 => array(4 => 1)),
        );

    }
}
