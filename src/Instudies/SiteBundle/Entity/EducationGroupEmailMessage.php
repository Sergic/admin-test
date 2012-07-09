<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroupBlogPost
 *
 * @ORM\Table(name="EducationGroupEmailMessage")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupEmailMessageRepository")
 */
class EducationGroupEmailMessage extends BaseEducationGroupEntryItemEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var string $fromEmail
     *
     * @ORM\Column(name="fromEmail", type="string", nullable=true)
     */
    protected $fromEmail;

    /**
     * @var string $toEmail
     *
     * @ORM\Column(name="toEmail", type="string", nullable=true)
     */
    protected $toEmail;

    /**
     * @var integer $size
     *
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    protected $size;

    /**
     * @ORM\OneToOne(targetEntity="EducationGroupBlogPost", inversedBy="educationGroupEmailMessage")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $educationGroupBlogPost;

    /**
     * @ORM\OneToOne(targetEntity="EducationGroupHomeworkPost", inversedBy="educationGroupEmailMessage")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $educationGroupHomeworkPost;

    /**
     * @ORM\OneToOne(targetEntity="EducationGroupSummaryPost", inversedBy="educationGroupEmailMessage")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $educationGroupSummaryPost;

    /**
     * @ORM\OneToOne(targetEntity="EducationGroupEventPost", inversedBy="educationGroupEmailMessage")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $educationGroupEventPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="emailMessages")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="educationGroupEmailMessage")
     */
    protected $activities;

    /**
     * @ORM\OneToMany(targetEntity="Favourite", mappedBy="educationGroupEmailMessage")
     */
    protected $favourites;

    /**
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="educationGroupEmailMessage")
     */
    protected $attachments;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedEducationGroupEmailMessage", mappedBy="educationGroupEmailMessage")
     */
    protected $unreaded;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="educationGroupEmailMessage")
     */
    protected $comments;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->unreaded = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->favourites = new ArrayCollection();
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public function niceTitle()
    {
        if ($this->title) {
            return $this->title;
        }
        return '(без темы)';
    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/



    /**
     * Set fromEmail
     *
     * @param string $fromEmail
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * Get fromEmail
     *
     * @return string 
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

    /**
     * Set toEmail
     *
     * @param string $toEmail
     */
    public function setToEmail($toEmail)
    {
        $this->toEmail = $toEmail;
    }

    /**
     * Get toEmail
     *
     * @return string 
     */
    public function getToEmail()
    {
        return $this->toEmail;
    }

    /**
     * Set size
     *
     * @param integer $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get size
     *
     * @return integer 
     */
    public function getSize()
    {
        return $this->size;
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
     * @param Instudies\SiteBundle\Entity\UnreadedEducationGroupEmailMessage $unreaded
     */
    public function addUnreadedEducationGroupEmailMessage(\Instudies\SiteBundle\Entity\UnreadedEducationGroupEmailMessage $unreaded)
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