<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroupEventPost
 *
 * @ORM\Table(name="EducationGroupEventPost")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupEventPostRepository")
 */
class EducationGroupEventPost extends BaseEducationGroupEntryItemEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @var time $time
     *
     * @ORM\Column(name="time", type="time", nullable=true)
     */
    protected $time;

    /**
     * @var endTime $endTime
     *
     * @ORM\Column(name="endTime", type="time", nullable=true)
     */
    protected $endTime;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="educationGroupEventPosts")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupEventCategory", inversedBy="eventPosts")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="posts")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @ORM\OneToOne(targetEntity="EducationGroupEmailMessage", mappedBy="educationGroupEventPost")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEmailMessage;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="educationGroupEventPost")
     */
    protected $activities;

    /**
     * @ORM\OneToMany(targetEntity="Favourite", mappedBy="educationGroupEventPost")
     */
    protected $favourites;

    /**
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="educationGroupEventPost")
     */
    protected $attachments;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedEducationGroupEventPost", mappedBy="educationGroupEventPost")
     */
    protected $unreaded;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="educationGroupEventPost")
     */
    protected $comments;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->favourites = new ArrayCollection();
        $this->unreaded = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public function archived()
    {

        $date = new \DateTime();
        $date->sub(new \DateInterval('P1D'));

        if ($this->date->getTimestamp() > $date->getTimestamp()) {
            return false;
        }

        return true;

    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/


    /**
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set time
     *
     * @param time $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Get time
     *
     * @return time 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set endTime
     *
     * @param time $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * Get endTime
     *
     * @return time 
     */
    public function getEndTime()
    {
        return $this->endTime;
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
     * Set category
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupEventCategory $category
     */
    public function setCategory(\Instudies\SiteBundle\Entity\EducationGroupEventCategory $category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupEventCategory 
     */
    public function getCategory()
    {
        return $this->category;
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
     * Add unreaded
     *
     * @param Instudies\SiteBundle\Entity\UnreadedEducationGroupEventPost $unreaded
     */
    public function addUnreadedEducationGroupEventPost(\Instudies\SiteBundle\Entity\UnreadedEducationGroupEventPost $unreaded)
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
}