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
 * @ORM\Table(name="EducationGroupBlogPost")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupBlogPostRepository")
 */
class EducationGroupBlogPost extends BaseEducationGroupEntryItemEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="educationGroupBlogPosts")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupBlogCategory", inversedBy="blogPosts")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="posts")
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
     * @var boolean $notificationComplete
     *
     * @ORM\Column(name="notificationComplete", type="boolean", nullable=true)
     */
    protected $notificationComplete;

    /**
     * @ORM\OneToOne(targetEntity="EducationGroupEmailMessage", mappedBy="educationGroupBlogPost")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEmailMessage;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="educationGroupBlogPost")
     */
    protected $activities;

    /**
     * @ORM\OneToMany(targetEntity="Favourite", mappedBy="educationGroupBlogPost")
     */
    protected $favourites;

    /**
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="educationGroupBlogPost")
     */
    protected $attachments;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedEducationGroupBlogPost", mappedBy="educationGroupBlogPost")
     */
    protected $unreaded;

    /**
     * @ORM\OneToMany(targetEntity="ReadedEducationGroupBlogPost", mappedBy="educationGroupBlogPost")
     */
    protected $readed;

    /**
     * @ORM\OneToMany(targetEntity="NotifiedEducationGroupBlogPost", mappedBy="educationGroupBlogPost")
     */
    protected $notified;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="educationGroupBlogPost")
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
     * GETTERS & SETTERS
     *********************************************************************************/


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
     * Set notificationComplete
     *
     * @param boolean $notificationComplete
     */
    public function setNotificationComplete($notificationComplete)
    {
        $this->notificationComplete = $notificationComplete;
    }

    /**
     * Get notificationComplete
     *
     * @return boolean 
     */
    public function getNotificationComplete()
    {
        return $this->notificationComplete;
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
     * @param Instudies\SiteBundle\Entity\EducationGroupBlogCategory $category
     */
    public function setCategory(\Instudies\SiteBundle\Entity\EducationGroupBlogCategory $category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupBlogCategory 
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
     * @param Instudies\SiteBundle\Entity\UnreadedEducationGroupBlogPost $unreaded
     */
    public function addUnreadedEducationGroupBlogPost(\Instudies\SiteBundle\Entity\UnreadedEducationGroupBlogPost $unreaded)
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
     * Add readed
     *
     * @param Instudies\SiteBundle\Entity\ReadedEducationGroupBlogPost $readed
     */
    public function addReadedEducationGroupBlogPost(\Instudies\SiteBundle\Entity\ReadedEducationGroupBlogPost $readed)
    {
        $this->readed[] = $readed;
    }

    /**
     * Get readed
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getReaded()
    {
        return $this->readed;
    }

    /**
     * Add notified
     *
     * @param Instudies\SiteBundle\Entity\NotifiedEducationGroupBlogPost $notified
     */
    public function addNotifiedEducationGroupBlogPost(\Instudies\SiteBundle\Entity\NotifiedEducationGroupBlogPost $notified)
    {
        $this->notified[] = $notified;
    }

    /**
     * Get notified
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getNotified()
    {
        return $this->notified;
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