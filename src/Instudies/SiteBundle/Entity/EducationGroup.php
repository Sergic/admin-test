<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroup
 *
 * @ORM\Table(name="EducationGroup")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupRepository")
 * @DoctrineAssert\UniqueEntity(fields="slug", message="Group slug should be unique")
 */
class EducationGroup extends BaseTimestampableDeletableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="UserEducationGroup", mappedBy="educationGroup")
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupSubject", mappedBy="educationGroup")
     */
    protected $subjects;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="educationGroup")
     */
    protected $activities;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="educationGroup")
     */
    protected $messages;

    /**
     * @ORM\OneToMany(targetEntity="Favourite", mappedBy="educationGroup")
     */
    protected $favourites;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="educationGroup")
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="educationGroup")
     */
    protected $notifications;

    /**
     * @ORM\OneToMany(targetEntity="Invitation", mappedBy="educationGroup")
     */
    protected $invitations;

    /**
     * @ORM\OneToMany(targetEntity="JoinEducationGroupRequest", mappedBy="educationGroup")
     */
    protected $joinEducationGroupRequests;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupSchedule", mappedBy="educationGroup")
     */
    protected $schedules;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupBlogPost", mappedBy="educationGroup")
     */
    protected $blogPosts;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupEmailMessage", mappedBy="educationGroup")
     */
    protected $emailMessages;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupEventPost", mappedBy="educationGroup")
     */
    protected $eventPosts;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupHomeworkPost", mappedBy="educationGroup")
     */
    protected $homeworkPosts;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupSummaryPost", mappedBy="educationGroup")
     */
    protected $summaryPosts;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->title;
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->educationGroupSubjects = new ArrayCollection();
        $this->educationGroupBlogPosts = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->favourites = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        $this->joinEducationGroupRequests = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->blogPosts = new ArrayCollection();
        $this->emailMessages = new ArrayCollection();
        $this->homeworkPosts = new ArrayCollection();
        $this->summaryPosts = new ArrayCollection();
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public function getCreator()
    {
        $users = $this->getUsers();
        foreach ($users as $user) {
            if ($user->getCreator()) {
                return $user->getUser();
            }
        }
    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add users
     *
     * @param Instudies\SiteBundle\Entity\UserEducationGroup $users
     */
    public function addUserEducationGroup(\Instudies\SiteBundle\Entity\UserEducationGroup $users)
    {
        $this->users[] = $users;
    }

    /**
     * Get users
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add subjects
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupSubject $subjects
     */
    public function addEducationGroupSubject(\Instudies\SiteBundle\Entity\EducationGroupSubject $subjects)
    {
        $this->subjects[] = $subjects;
    }

    /**
     * Get subjects
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Add activities
     *
     * @param Instudies\SiteBundle\Entity\Activity $activities
     */
    public function addActivity(\Instudies\SiteBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;
    }

    /**
     * Get activities
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getActivities()
    {
        return $this->activities;
    }

    /**
     * Add comments
     *
     * @param Instudies\SiteBundle\Entity\Comment $comments
     */
    public function addComment(\Instudies\SiteBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;
    }

    /**
     * Get comments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add notifications
     *
     * @param Instudies\SiteBundle\Entity\Notification $notifications
     */
    public function addNotification(\Instudies\SiteBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;
    }

    /**
     * Get notifications
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Add invitations
     *
     * @param Instudies\SiteBundle\Entity\Invitation $invitations
     */
    public function addInvitation(\Instudies\SiteBundle\Entity\Invitation $invitations)
    {
        $this->invitations[] = $invitations;
    }

    /**
     * Get invitations
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getInvitations()
    {
        return $this->invitations;
    }

    /**
     * Add joinEducationGroupRequests
     *
     * @param Instudies\SiteBundle\Entity\JoinEducationGroupRequest $joinEducationGroupRequests
     */
    public function addJoinEducationGroupRequest(\Instudies\SiteBundle\Entity\JoinEducationGroupRequest $joinEducationGroupRequests)
    {
        $this->joinEducationGroupRequests[] = $joinEducationGroupRequests;
    }

    /**
     * Get joinEducationGroupRequests
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getJoinEducationGroupRequests()
    {
        return $this->joinEducationGroupRequests;
    }

    /**
     * Add blogPosts
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupBlogPost $blogPosts
     */
    public function addEducationGroupBlogPost(\Instudies\SiteBundle\Entity\EducationGroupBlogPost $blogPosts)
    {
        $this->blogPosts[] = $blogPosts;
    }

    /**
     * Get blogPosts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBlogPosts()
    {
        return $this->blogPosts;
    }

    /**
     * Add homeworkPosts
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupHomeworkPost $homeworkPosts
     */
    public function addEducationGroupHomeworkPost(\Instudies\SiteBundle\Entity\EducationGroupHomeworkPost $homeworkPosts)
    {
        $this->homeworkPosts[] = $homeworkPosts;
    }

    /**
     * Get homeworkPosts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getHomeworkPosts()
    {
        return $this->homeworkPosts;
    }

    /**
     * Add summaryPosts
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupSummaryPost $summaryPosts
     */
    public function addEducationGroupSummaryPost(\Instudies\SiteBundle\Entity\EducationGroupSummaryPost $summaryPosts)
    {
        $this->summaryPosts[] = $summaryPosts;
    }

    /**
     * Get summaryPosts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSummaryPosts()
    {
        return $this->summaryPosts;
    }

    /**
     * Add favourites
     *
     * @param Instudies\SiteBundle\Entity\Favourite $favourites
     */
    public function addFavourite(\Instudies\SiteBundle\Entity\Favourite $favourites)
    {
        $this->favourites[] = $favourites;
    }

    /**
     * Get favourites
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFavourites()
    {
        return $this->favourites;
    }

    /**
     * Add eventPosts
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupEventPost $eventPosts
     */
    public function addEducationGroupEventPost(\Instudies\SiteBundle\Entity\EducationGroupEventPost $eventPosts)
    {
        $this->eventPosts[] = $eventPosts;
    }

    /**
     * Get eventPosts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEventPosts()
    {
        return $this->eventPosts;
    }

    /**
     * Add messages
     *
     * @param Instudies\SiteBundle\Entity\Message $messages
     */
    public function addMessage(\Instudies\SiteBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;
    }

    /**
     * Get messages
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add schedules
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupSchedule $schedules
     */
    public function addEducationGroupSchedule(\Instudies\SiteBundle\Entity\EducationGroupSchedule $schedules)
    {
        $this->schedules[] = $schedules;
    }

    /**
     * Get schedules
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSchedules()
    {
        return $this->schedules;
    }

    /**
     * Add emailMessages
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupEmailMessage $emailMessages
     */
    public function addEducationGroupEmailMessage(\Instudies\SiteBundle\Entity\EducationGroupEmailMessage $emailMessages)
    {
        $this->emailMessages[] = $emailMessages;
    }

    /**
     * Get emailMessages
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEmailMessages()
    {
        return $this->emailMessages;
    }
}