<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\Comment
 *
 * @ORM\Table(name="Comment")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\CommentRepository")
 */
class Comment extends BaseTimestampableDeletableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var text $text
     *
     * @ORM\Column(name="text", type="text")
     */
    protected $text;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     */
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupEmailMessage", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEmailMessage;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupBlogPost", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupBlogPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupHomeworkPost", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupHomeworkPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSummaryPost", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupSummaryPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupEventPost", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEventPost;

    /**
     * @ORM\ManyToOne(targetEntity="Feedback", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $feedback;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="comment")
     */
    protected $activities;

    /**
     * @ORM\OneToMany(targetEntity="Favourite", mappedBy="comment")
     */
    protected $favourites;

    /**
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="comment")
     */
    protected $attachments;

    /**
     * @ORM\OneToMany(targetEntity="ReadedComment", mappedBy="comment")
     */
    protected $readedComment;

    public $isReaded;

    public $creationActivityIsReaded;

    public $inMyFavorites;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->text;
    }

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->favourites = new ArrayCollection();
        $this->readed = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public function getRouteName ()
    {

        if (count($this->getEducationGroupBlogPost()) > 0) {
            if ($this->getEducationGroupBlogPost()->getEducationGroupAssociated()) {
                return 'group_blog_single';
            } else {
                return 'public_blog_single';
            }
        }elseif (count($this->getEducationGroupEventPost()) > 0) {
            return 'group_event_single';
        }elseif (count($this->getEducationGroupHomeworkPost()) > 0) {
            return 'group_homework_single';
        }elseif (count($this->getEducationGroupSummaryPost()) > 0) {
            return 'group_summary_single';
        }elseif (count($this->getFeedback()) > 0) {
            return 'feedback_single';
        }elseif (count($this->getEducationGroupEmailMessage()) > 0) {
            return 'group_email_single';
        }

    }

    public function getRouteParams ()
    {

        if (count($this->getEducationGroupBlogPost()) > 0) {
            return array('id' => $this->getEducationGroupBlogPost()->getId());
        }elseif (count($this->getEducationGroupEventPost()) > 0) {
            return array('id' => $this->getEducationGroupEventPost()->getId());
        }elseif (count($this->getEducationGroupHomeworkPost()) > 0) {
            return array('id' => $this->getEducationGroupHomeworkPost()->getId());
        }elseif (count($this->getEducationGroupSummaryPost()) > 0) {
            return array('id' => $this->getEducationGroupSummaryPost()->getId());
        }elseif (count($this->getFeedback()) > 0) {
            return array('id' => $this->getFeedback()->getId());
        }elseif (count($this->getEducationGroupEmailMessage()) > 0) {
            return array('id' => $this->getEducationGroupEmailMessage()->getId());
        }

    }

    public function getRouteHash ()
    {

        return '#comment-' . $this->getId();

    }

    public function getLink ($controller)
    {

        return $controller->generateUrl($this->getRouteName(), $this->getRouteParams()) . $this->getRouteHash();

    }

    public function entryTitle ()
    {
        if (count($this->getEducationGroupBlogPost()) > 0) {
            return $this->getEducationGroupBlogPost()->getTitle();
        }elseif (count($this->getEducationGroupEventPost()) > 0) {
            return $this->getEducationGroupEventPost()->getTitle();
        }elseif (count($this->getEducationGroupHomeworkPost()) > 0) {
            return $this->getEducationGroupHomeworkPost()->getTitle();
        }elseif (count($this->getEducationGroupSummaryPost()) > 0) {
            return $this->getEducationGroupSummaryPost()->getTitle();
        }elseif (count($this->getFeedback()) > 0) {
            return $this->getFeedback()->getTitle();
        }elseif (count($this->getEducationGroupEmailMessage()) > 0) {
            return $this->getEducationGroupEmailMessage()->niceTitle();
        }
    }

    public function type ()
    {

        if ($this->educationGroupBlogPost) {
            return 'EducationGroupBlogPost';
        } elseif ($this->educationGroupEventPost) {
            return 'EducationGroupEventPost';
        } elseif ($this->educationGroupHomeworkPost) {
            return 'EducationGroupHomeworkPost';
        } elseif ($this->educationGroupSummaryPost) {
            return 'EducationGroupSummaryPost';
        } elseif ($this->feedback) {
            return 'Feedback';
        } elseif ($this->educationGroupEmailMessage) {
            return 'EducationGroupEmailMessage';
        }

    }

    public function contentId ()
    {

        if ($this->educationGroupBlogPost) {
            return $this->educationGroupBlogPost->getId();
        } elseif ($this->educationGroupEventPost) {
            return $this->educationGroupEventPost->getId();
        } elseif ($this->educationGroupHomeworkPost) {
            return $this->educationGroupHomeworkPost->getId();
        } elseif ($this->educationGroupSummaryPost) {
            return $this->educationGroupSummaryPost->getId();
        } elseif ($this->feedback) {
            return $this->feedback->getId();
        } elseif ($this->educationGroupEmailMessage) {
            return $this->educationGroupEmailMessage->getId();
        }

    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

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
     * Set parent
     *
     * @param Instudies\SiteBundle\Entity\Comment $parent
     */
    public function setParent(\Instudies\SiteBundle\Entity\Comment $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Instudies\SiteBundle\Entity\Comment 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param Instudies\SiteBundle\Entity\Comment $children
     */
    public function addComment(\Instudies\SiteBundle\Entity\Comment $children)
    {
        $this->children[] = $children;
    }

    /**
     * Get children
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
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
     * Add readedComment
     *
     * @param Instudies\SiteBundle\Entity\ReadedComment $readedComment
     */
    public function addReadedComment(\Instudies\SiteBundle\Entity\ReadedComment $readedComment)
    {
        $this->readedComment[] = $readedComment;
    }

    /**
     * Get readedComment
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getReadedComment()
    {
        return $this->readedComment;
    }
}