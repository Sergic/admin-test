<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroupSubject
 *
 * @ORM\Table(name="EducationGroupSubject")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupSubjectRepository")
 */
class EducationGroupSubject extends BaseTimestampableDeletableIdableEntity
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
     * @var string $text
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    protected $text;

    /**
     * @var datetime $archived
     *
     * @ORM\Column(name="archived", type="boolean", nullable=true)
     */
    protected $archived;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="subjects")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupTeacher", mappedBy="subject")
     */
    protected $teachers;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupSubjectTopic", mappedBy="subject")
     */
    protected $topics;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupSchedule", mappedBy="subject")
     */
    protected $schedules;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupHomeworkPost", mappedBy="subject")
     */
    protected $homeworkPosts;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="educationGroupSubject")
     */
    protected $activities;

    /**
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="educationGroupSubject")
     */
    protected $attachments;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupSummaryPost", mappedBy="subject")
     */
    protected $summaryPosts;

    public $isReaded;

    public $topicsNum;

    public $topicsNewNum;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->title;
    }

    public function __construct()
    {
        $this->teachers = new ArrayCollection();
        $this->topics = new ArrayCollection();
        $this->homeworkPosts = new ArrayCollection();
        $this->summaryPosts = new ArrayCollection();
        $this->readed = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->schedules = new ArrayCollection();
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    protected $textParts;

    public function cutedText()
    {
        $this->textParts = explode('<cut>', $this->text);
        return $this->textParts[0];
    }

    public function hasContinue()
    {
        if (count($this->textParts) > 1) {
            return true;
        }
        return false;
    }

    public $actualPostsCount;

    public $archivedPostsCount;

    public function totalPostsCount() {
        return $actualPostsCount + $archivedPostsCount;
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
     * Set text
     *
     * @param text $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return text 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set archived
     *
     * @param boolean $archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * Get archived
     *
     * @return boolean 
     */
    public function getArchived()
    {
        return $this->archived;
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
     * Add teachers
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupTeacher $teachers
     */
    public function addEducationGroupTeacher(\Instudies\SiteBundle\Entity\EducationGroupTeacher $teachers)
    {
        $this->teachers[] = $teachers;
    }

    /**
     * Get teachers
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTeachers()
    {
        return $this->teachers;
    }

    /**
     * Add topics
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupSubjectTopic $topics
     */
    public function addEducationGroupSubjectTopic(\Instudies\SiteBundle\Entity\EducationGroupSubjectTopic $topics)
    {
        $this->topics[] = $topics;
    }

    /**
     * Get topics
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTopics()
    {
        return $this->topics;
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
     * Add attachments
     *
     * @param Instudies\SiteBundle\Entity\Attachment $attachments
     */
    public function addAttachment(\Instudies\SiteBundle\Entity\Attachment $attachments)
    {
        $this->attachments[] = $attachments;
    }

    /**
     * Get attachments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAttachments()
    {
        return $this->attachments;
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
}