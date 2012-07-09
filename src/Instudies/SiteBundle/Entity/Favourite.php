<?php

namespace Instudies\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Instudies\SiteBundle\Entity\Favourite
 *
 * @ORM\Table(name="Favourite")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\FavouriteRepository")
 */
class Favourite extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="myFavourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $owner;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="favourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="favourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupEmailMessage", inversedBy="favourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEmailMessage;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupBlogPost", inversedBy="favourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupBlogPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupHomeworkPost", inversedBy="favourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupHomeworkPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSummaryPost", inversedBy="favourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupSummaryPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupEventPost", inversedBy="favourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEventPost;

    /**
     * @ORM\ManyToOne(targetEntity="Feedback", inversedBy="favourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $feedback;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="favourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="favourites")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $message;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->title;
    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set owner
     *
     * @param Instudies\SiteBundle\Entity\User $owner
     */
    public function setOwner(\Instudies\SiteBundle\Entity\User $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return Instudies\SiteBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
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
     * Set message
     *
     * @param Instudies\SiteBundle\Entity\Message $message
     */
    public function setMessage(\Instudies\SiteBundle\Entity\Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return Instudies\SiteBundle\Entity\Message 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set feedback
     *
     * @param Instudies\SiteBundle\Entity\Feedback $feedback
     */
    public function setFeedback(\Instudies\SiteBundle\Entity\Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Get feedback
     *
     * @return Instudies\SiteBundle\Entity\Feedback 
     */
    public function getFeedback()
    {
        return $this->feedback;
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
}