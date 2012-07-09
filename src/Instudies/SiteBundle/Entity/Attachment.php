<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\Attachment
 *
 * @ORM\Table(name="Attachment")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\AttachmentRepository")
 */
class Attachment extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var string $rand
     *
     * @ORM\Column(name="rand", type="string", length=255)
     */
    protected $rand;

    /**
     * @var string $tempIdenty
     *
     * @ORM\Column(name="tempIdenty", type="string", length=255, nullable=true)
     */
    protected $tempIdenty;

    /**
     * @var string $fileName
     *
     * @ORM\Column(name="fileName", type="string", length=255)
     */
    protected $fileName;

    /**
     * @var integer $fileSize
     *
     * @ORM\Column(name="fileSize", type="integer")
     */
    protected $fileSize;

    /**
     * @var boolean $isImage
     *
     * @ORM\Column(name="isImage", type="boolean", nullable=true)
     */
    protected $isImage;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupEmailMessage", inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEmailMessage;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupBlogPost", inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupBlogPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupHomeworkPost", inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupHomeworkPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSummaryPost", inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupSummaryPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupEventPost", inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEventPost;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSubject", inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupSubject;

    /**
     * @ORM\ManyToOne(targetEntity="Message", inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Feedback", inversedBy="attachments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $feedback;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->fileName;
    }

    public function __construct()
    {
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public function getPath()
    {
        return $this->link();
    }

    public function link()
    {
        if ($this->getUser()) {
            return $this->getUser()->getId() . '/' . $this->getRand() . '/' . $this->getFileName();
        }
        return 'emailattachment/' . $this->getRand() . '/' . $this->getFileName();
    }

    public function sizeInMb()
    {
        return round($this->getFileSize() / 1048576, 2);
    }

    public function sizeInKb()
    {
        return round($this->getFileSize() / 1024, 2);
    }

    public function extension()
    {
        $parts = explode('.',$this->fileName);
        return strtolower(end($parts));
    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set rand
     *
     * @param string $rand
     */
    public function setRand($rand)
    {
        $this->rand = $rand;
    }

    /**
     * Get rand
     *
     * @return string 
     */
    public function getRand()
    {
        return $this->rand;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set fileSize
     *
     * @param integer $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * Get fileSize
     *
     * @return integer 
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set isImage
     *
     * @param boolean $isImage
     */
    public function setIsImage($isImage)
    {
        $this->isImage = $isImage;
    }

    /**
     * Get isImage
     *
     * @return boolean 
     */
    public function getIsImage()
    {
        return $this->isImage;
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
     * Set tempIdenty
     *
     * @param string $tempIdenty
     */
    public function setTempIdenty($tempIdenty)
    {
        $this->tempIdenty = $tempIdenty;
    }

    /**
     * Get tempIdenty
     *
     * @return string 
     */
    public function getTempIdenty()
    {
        return $this->tempIdenty;
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