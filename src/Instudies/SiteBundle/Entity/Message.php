<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\Message
 *
 * @ORM\Table(name="Message")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\MessageRepository")
 */
class Message extends  BaseTimestampableIdableEntity
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sendedMessages")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $sender;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="recievedMessages")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $reciever;

    /**
     * @var boolean $readed
     *
     * @ORM\Column(name="readed", type="boolean", nullable=true)
     */
    protected $readed;

    /**
     * @var boolean $recieverNotified
     *
     * @ORM\Column(name="recieverNotified", type="boolean", nullable=true)
     */
    protected $recieverNotified;

    /**
     * @var boolean $deletedBySender
     *
     * @ORM\Column(name="deletedBySender", type="boolean", nullable=true)
     */
    protected $deletedBySender;

    /**
     * @var boolean $deletedByReciever
     *
     * @ORM\Column(name="deletedByReciever", type="boolean", nullable=true)
     */
    protected $deletedByReciever;

    /**
     * @ORM\OneToMany(targetEntity="Favourite", mappedBy="message")
     */
    protected $favourites;

    /**
     * @ORM\OneToMany(targetEntity="Attachment", mappedBy="message")
     */
    protected $attachments;

    /**
     * @ORM\ManyToOne(targetEntity="Conversation")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $conversation;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="messages")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    public $inMyFavorites;

    public $isReaded;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->text;
    }

    public function __construct()
    {
        $this->favourites = new ArrayCollection();
        $this->attachments = new ArrayCollection();
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public function interlocutor($userId)
    {

        if ($this->sender->getId() == $userId) {
            return array(
                    'role' => 'sender',
                    'user' => $this->reciever
                );
        } else {
            return array(
                    'role' => 'reciever',
                    'user' => $this->sender
                );
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
     * Set readed
     *
     * @param boolean $readed
     */
    public function setReaded($readed)
    {
        $this->readed = $readed;
    }

    /**
     * Get readed
     *
     * @return boolean 
     */
    public function getReaded()
    {
        return $this->readed;
    }

    /**
     * Set deletedBySender
     *
     * @param boolean $deletedBySender
     */
    public function setDeletedBySender($deletedBySender)
    {
        $this->deletedBySender = $deletedBySender;
    }

    /**
     * Get deletedBySender
     *
     * @return boolean 
     */
    public function getDeletedBySender()
    {
        return $this->deletedBySender;
    }

    /**
     * Set deletedByReciever
     *
     * @param boolean $deletedByReciever
     */
    public function setDeletedByReciever($deletedByReciever)
    {
        $this->deletedByReciever = $deletedByReciever;
    }

    /**
     * Get deletedByReciever
     *
     * @return boolean 
     */
    public function getDeletedByReciever()
    {
        return $this->deletedByReciever;
    }

    /**
     * Set sender
     *
     * @param Instudies\SiteBundle\Entity\User $sender
     */
    public function setSender(\Instudies\SiteBundle\Entity\User $sender)
    {
        $this->sender = $sender;
    }

    /**
     * Get sender
     *
     * @return Instudies\SiteBundle\Entity\User 
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set reciever
     *
     * @param Instudies\SiteBundle\Entity\User $reciever
     */
    public function setReciever(\Instudies\SiteBundle\Entity\User $reciever)
    {
        $this->reciever = $reciever;
    }

    /**
     * Get reciever
     *
     * @return Instudies\SiteBundle\Entity\User 
     */
    public function getReciever()
    {
        return $this->reciever;
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
     * Set conversation
     *
     * @param Instudies\SiteBundle\Entity\Conversation $conversation
     */
    public function setConversation(\Instudies\SiteBundle\Entity\Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    /**
     * Get conversation
     *
     * @return Instudies\SiteBundle\Entity\Conversation 
     */
    public function getConversation()
    {
        return $this->conversation;
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
     * Set recieverNotified
     *
     * @param boolean $recieverNotified
     */
    public function setRecieverNotified($recieverNotified)
    {
        $this->recieverNotified = $recieverNotified;
    }

    /**
     * Get recieverNotified
     *
     * @return boolean 
     */
    public function getRecieverNotified()
    {
        return $this->recieverNotified;
    }
}