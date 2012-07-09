<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\Invitation
 *
 * @ORM\Table(name="Invitation")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\InvitationRepository")
 * @DoctrineAssert\UniqueEntity(fields="code")
 */
class Invitation extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var text $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var text $code
     *
     * @ORM\Column(name="code", type="text")
     */
    protected $code;

    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean", nullable=true)
     */
    protected $active;

    /**
     * @var boolean $accepted
     *
     * @ORM\Column(name="accepted", type="boolean", nullable=true)
     */
    protected $accepted;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invitations")
     * @ORM\JoinColumn(name="sender", referencedColumnName="id")
     */
    protected $sender;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="invitations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @ORM\OneToOne(targetEntity="Notification", mappedBy="invitation")
     */
    protected $notification;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->code;
    }

    public function __construct()
    {

    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set code
     *
     * @param text $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return text 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
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
     * Set active
     *
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
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
     * Set accepted
     *
     * @param boolean $accepted
     */
    public function setAccepted($accepted)
    {
        $this->accepted = $accepted;
    }

    /**
     * Get accepted
     *
     * @return boolean 
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    /**
     * Set notification
     *
     * @param Instudies\SiteBundle\Entity\Notification $notification
     */
    public function setNotification(\Instudies\SiteBundle\Entity\Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get notification
     *
     * @return Instudies\SiteBundle\Entity\Notification 
     */
    public function getNotification()
    {
        return $this->notification;
    }
}