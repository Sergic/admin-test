<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroupSummaryPost
 *
 * @ORM\Table(name="EducationGroupSummaryPost")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupSummaryPostRepository")
 */
class EducationGroupSummaryPost extends BaseEducationGroupEntryItemEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="educationGroupSummaryPosts")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSubject", inversedBy="summaryPosts")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $subject;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="posts")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @ORM\OneToOne(targetEntity="EducationGroupEmailMessage", mappedBy="educationGroupSummaryPost")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEmailMessage;


    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="educationGroupSummaryPost")
     */
    protected $activities;

    /**
     * @ORM\OneToMany(targetEntity="Favourite", mappedBy="educationGroupSummaryPost")
     */
    protected $favourites;

    /**
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="educationGroupSummaryPost")
     */
    protected $attachments;

    /**
     * @ORM\OneToMany(targetEntity="UnreadedEducationGroupSummaryPost", mappedBy="educationGroupSummaryPost")
     */
    protected $unreaded;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="educationGroupSummaryPost")
     */
    protected $comments;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __construct()
    {
        $this->activities = new ArrayCollection();
        $this->favourites = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/



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
     * @param Instudies\SiteBundle\Entity\UnreadedEducationGroupSummaryPost $unreaded
     */
    public function addUnreadedEducationGroupSummaryPost(\Instudies\SiteBundle\Entity\UnreadedEducationGroupSummaryPost $unreaded)
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