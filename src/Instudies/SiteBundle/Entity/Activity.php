<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\Activity
 *
 * @ORM\Table(name="Activity")
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\ActivityRepository")
 */
class Activity extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var boolean $setReadedAfterRead
     *
     * @ORM\Column(name="setReadedAfterRead", type="boolean", nullable=true)
     */
    protected $setReadedAfterRead;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupEmailMessage", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEmailMessage;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupBlogPost", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupBlogPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupHomeworkPost", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupHomeworkPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSummaryPost", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupSummaryPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupEventPost", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEventPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSubject", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupSubject;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSubjectTopic", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupSubjectTopic;

    /**
     * @var datetime $scheduleDate
     *
     * @ORM\Column(name="scheduleDate", type="datetime", nullable=true)
     */
    protected $scheduleDate;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @var boolean $educationGroupAssociated
     *
     * @ORM\Column(name="educationGroupAssociated", type="boolean", nullable=true)
     */
    protected $educationGroupAssociated;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedActivity", mappedBy="activity")
     */
    protected $unreaded;

    public $isReaded;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return '';
    }

    public function __construct()
    {
        $this->unreaded = new ArrayCollection();
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set setReadedAfterRead
     *
     * @param boolean $setReadedAfterRead
     */
    public function setSetReadedAfterRead($setReadedAfterRead)
    {
        $this->setReadedAfterRead = $setReadedAfterRead;
    }

    /**
     * Get setReadedAfterRead
     *
     * @return boolean 
     */
    public function getSetReadedAfterRead()
    {
        return $this->setReadedAfterRead;
    }

    /**
     * Set scheduleDate
     *
     * @param datetime $scheduleDate
     */
    public function setScheduleDate($scheduleDate)
    {
        $this->scheduleDate = $scheduleDate;
    }

    /**
     * Get scheduleDate
     *
     * @return datetime 
     */
    public function getScheduleDate()
    {
        return $this->scheduleDate;
    }

    /**
     * Set educationGroupAssociated
     *
     * @param boolean $educationGroupAssociated
     */
    public function setEducationGroupAssociated($educationGroupAssociated)
    {
        $this->educationGroupAssociated = $educationGroupAssociated;
    }

    /**
     * Get educationGroupAssociated
     *
     * @return boolean 
     */
    public function getEducationGroupAssociated()
    {
        return $this->educationGroupAssociated;
    }

    /**
     * Set user
     *
     * @param Instudies\SiteBundle\Entity\User $user
     */
    public function setUser(\Instudies\SiteBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Instudies\SiteBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set comment
     *
     * @param Instudies\SiteBundle\Entity\Comment $comment
     */
    public function setComment(\Instudies\SiteBundle\Entity\Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return Instudies\SiteBundle\Entity\Comment 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set educationGroupEmailMessage
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupEmailMessage $educationGroupEmailMessage
     */
    public function setEducationGroupEmailMessage(\Instudies\SiteBundle\Entity\EducationGroupEmailMessage $educationGroupEmailMessage)
    {
        $this->educationGroupEmailMessage = $educationGroupEmailMessage;
    }

    /**
     * Get educationGroupEmailMessage
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupEmailMessage 
     */
    public function getEducationGroupEmailMessage()
    {
        return $this->educationGroupEmailMessage;
    }

    /**
     * Set educationGroupBlogPost
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupBlogPost $educationGroupBlogPost
     */
    public function setEducationGroupBlogPost(\Instudies\SiteBundle\Entity\EducationGroupBlogPost $educationGroupBlogPost)
    {
        $this->educationGroupBlogPost = $educationGroupBlogPost;
    }

    /**
     * Get educationGroupBlogPost
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupBlogPost 
     */
    public function getEducationGroupBlogPost()
    {
        return $this->educationGroupBlogPost;
    }

    /**
     * Set educationGroupHomeworkPost
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupHomeworkPost $educationGroupHomeworkPost
     */
    public function setEducationGroupHomeworkPost(\Instudies\SiteBundle\Entity\EducationGroupHomeworkPost $educationGroupHomeworkPost)
    {
        $this->educationGroupHomeworkPost = $educationGroupHomeworkPost;
    }

    /**
     * Get educationGroupHomeworkPost
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupHomeworkPost 
     */
    public function getEducationGroupHomeworkPost()
    {
        return $this->educationGroupHomeworkPost;
    }

    /**
     * Set educationGroupSummaryPost
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupSummaryPost $educationGroupSummaryPost
     */
    public function setEducationGroupSummaryPost(\Instudies\SiteBundle\Entity\EducationGroupSummaryPost $educationGroupSummaryPost)
    {
        $this->educationGroupSummaryPost = $educationGroupSummaryPost;
    }

    /**
     * Get educationGroupSummaryPost
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupSummaryPost 
     */
    public function getEducationGroupSummaryPost()
    {
        return $this->educationGroupSummaryPost;
    }

    /**
     * Set educationGroupEventPost
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupEventPost $educationGroupEventPost
     */
    public function setEducationGroupEventPost(\Instudies\SiteBundle\Entity\EducationGroupEventPost $educationGroupEventPost)
    {
        $this->educationGroupEventPost = $educationGroupEventPost;
    }

    /**
     * Get educationGroupEventPost
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupEventPost 
     */
    public function getEducationGroupEventPost()
    {
        return $this->educationGroupEventPost;
    }

    /**
     * Set educationGroupSubject
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupSubject $educationGroupSubject
     */
    public function setEducationGroupSubject(\Instudies\SiteBundle\Entity\EducationGroupSubject $educationGroupSubject)
    {
        $this->educationGroupSubject = $educationGroupSubject;
    }

    /**
     * Get educationGroupSubject
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupSubject 
     */
    public function getEducationGroupSubject()
    {
        return $this->educationGroupSubject;
    }

    /**
     * Set educationGroupSubjectTopic
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupSubjectTopic $educationGroupSubjectTopic
     */
    public function setEducationGroupSubjectTopic(\Instudies\SiteBundle\Entity\EducationGroupSubjectTopic $educationGroupSubjectTopic)
    {
        $this->educationGroupSubjectTopic = $educationGroupSubjectTopic;
    }

    /**
     * Get educationGroupSubjectTopic
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupSubjectTopic 
     */
    public function getEducationGroupSubjectTopic()
    {
        return $this->educationGroupSubjectTopic;
    }

    /**
     * Set educationGroup
     *
     * @param Instudies\SiteBundle\Entity\EducationGroup $educationGroup
     */
    public function setEducationGroup(\Instudies\SiteBundle\Entity\EducationGroup $educationGroup)
    {
        $this->educationGroup = $educationGroup;
    }

    /**
     * Get educationGroup
     *
     * @return Instudies\SiteBundle\Entity\EducationGroup 
     */
    public function getEducationGroup()
    {
        return $this->educationGroup;
    }

    /**
     * Add unreaded
     *
     * @param Instudies\SiteBundle\Entity\UnreadedActivity $unreaded
     */
    public function addUnreadedActivity(\Instudies\SiteBundle\Entity\UnreadedActivity $unreaded)
    {
        $this->unreaded[] = $unreaded;
    }

    /**
     * Get unreaded
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUnreaded()
    {
        return $this->unreaded;
    }
}