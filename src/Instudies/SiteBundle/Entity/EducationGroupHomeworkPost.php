<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroupHomeworkPost
 *
 * @ORM\Table(name="EducationGroupHomeworkPost")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupHomeworkPostRepository")
 */
class EducationGroupHomeworkPost extends BaseEducationGroupEntryItemEntity
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="educationGroupHomeworkPosts")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSubject", inversedBy="homeworkPosts")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $subject;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="posts")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @ORM\OneToOne(targetEntity="EducationGroupEmailMessage", mappedBy="educationGroupHomeworkPost")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEmailMessage;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="educationGroupHomeworkPost")
     */
    protected $activities;

    /**
     * @ORM\OneToMany(targetEntity="Favourite", mappedBy="educationGroupHomeworkPost")
     */
    protected $favourites;

    /**
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="educationGroupHomeworkPost")
     */
    protected $attachments;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedEducationGroupHomeworkPost", mappedBy="educationGroupHomeworkPost")
     */
    protected $unreaded;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="educationGroupHomeworkPost")
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
     * Set subject
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupSubject $subject
     */
    public function setSubject(\Instudies\SiteBundle\Entity\EducationGroupSubject $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get subject
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupSubject 
     */
    public function getSubject()
    {
        return $this->subject;
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
     * @param Instudies\SiteBundle\Entity\UnreadedEducationGroupHomeworkPost $unreaded
     */
    public function addUnreadedEducationGroupHomeworkPost(\Instudies\SiteBundle\Entity\UnreadedEducationGroupHomeworkPost $unreaded)
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