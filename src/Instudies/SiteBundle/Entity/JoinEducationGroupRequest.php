<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\JoinEducationGroupRequest
 *
 * @ORM\Table(name="JoinEducationGroupRequest")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\JoinEducationGroupRequestRepository")
 */
class JoinEducationGroupRequest extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="joinEducationGroupRequests")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="joinEducationGroupRequests")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="joinEducationGroupRequest")
     */
    protected $notifications;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->user->getFullname() . ' - ' . $this->group->getTitle();
    }

    public function __construct()
    {
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

    /**
     * Add notifications
     *
     * @param Instudies\SiteBundle\Entity\Notification $notifications
     */
    public function addNotification(\Instudies\SiteBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;
    }

    /**
     * Get notifications
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
}