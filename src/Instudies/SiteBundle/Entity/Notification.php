<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\Notification
 *
 * @ORM\Table(name="Notification")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\NotificationRepository")
 */
class Notification extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var text $type
     *
     * @ORM\Column(name="type", type="text")
     */
    protected $type;

    /**
     * @var datetime $deleteAfterRead
     *
     * @ORM\Column(name="deleteAfterRead", type="boolean", nullable=true)
     */
    protected $deleteAfterRead;

    /**
     * @ORM\OneToOne(targetEntity="Invitation", inversedBy="notification")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $invitation;

    /**
     * @ORM\ManyToOne(targetEntity="JoinEducationGroupRequest", inversedBy="notifications")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $joinEducationGroupRequest;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="recievedNotifications")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $reciever;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sendedNotifications")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $sender;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="notifications")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->type;
    }

    public function __construct()
    {

    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set type
     *
     * @param text $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return text 
     */
    public function getType()
    {
        return $this->type;
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
     * Set deleteAfterRead
     *
     * @param boolean $deleteAfterRead
     */
    public function setDeleteAfterRead($deleteAfterRead)
    {
        $this->deleteAfterRead = $deleteAfterRead;
    }

    /**
     * Get deleteAfterRead
     *
     * @return boolean 
     */
    public function getDeleteAfterRead()
    {
        return $this->deleteAfterRead;
    }

    /**
     * Set invitation
     *
     * @param Instudies\SiteBundle\Entity\Invitation $invitation
     */
    public function setInvitation(\Instudies\SiteBundle\Entity\Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get invitation
     *
     * @return Instudies\SiteBundle\Entity\Invitation 
     */
    public function getInvitation()
    {
        return $this->invitation;
    }

    /**
     * Set joinEducationGroupRequest
     *
     * @param Instudies\SiteBundle\Entity\JoinEducationGroupRequest $joinEducationGroupRequest
     */
    public function setJoinEducationGroupRequest(\Instudies\SiteBundle\Entity\JoinEducationGroupRequest $joinEducationGroupRequest)
    {
        $this->joinEducationGroupRequest = $joinEducationGroupRequest;
    }

    /**
     * Get joinEducationGroupRequest
     *
     * @return Instudies\SiteBundle\Entity\JoinEducationGroupRequest 
     */
    public function getJoinEducationGroupRequest()
    {
        return $this->joinEducationGroupRequest;
    }
}