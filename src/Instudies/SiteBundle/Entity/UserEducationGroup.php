<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\UserEducationGroup
 *
 * @ORM\Table(name="UserEducationGroup")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupRepository")
 */
class UserEducationGroup extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var boolean $inviteWidgetHidden
     *
     * @ORM\Column(name="inviteWidgetHidden", type="boolean", nullable=true)
     */
    protected $inviteWidgetHidden;

    /**
     * @var boolean $defaultScheduleAlertHidden
     *
     * @ORM\Column(name="defaultScheduleAlertHidden", type="boolean", nullable=true)
     */
    protected $defaultScheduleAlertHidden;

    /**
     * @var boolean $creator
     *
     * @ORM\Column(name="creator", type="boolean", nullable=true)
     */
    protected $creator;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="groups")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="users")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**
     * @var boolean $permissionEmails
     *
     * @ORM\Column(name="permissionEmails", type="boolean", nullable=true)
     */
    protected $permissionEmails;

    /**
     * @var boolean $permissionOwnHomeworks
     *
     * @ORM\Column(name="permissionOwnHomeworks", type="boolean", nullable=true)
     */
    protected $permissionOwnHomeworks;

    /**
     * @var boolean $permissionOwnBlogs
     *
     * @ORM\Column(name="permissionOwnBlogs", type="boolean", nullable=true)
     */
    protected $permissionOwnBlogs;

    /**
     * @var boolean $permissionOwnSummaries
     *
     * @ORM\Column(name="permissionOwnSummaries", type="boolean", nullable=true)
     */
    protected $permissionOwnSummaries;

    /**
     * @var boolean $permissionOwnEvents
     *
     * @ORM\Column(name="permissionOwnEvents", type="boolean", nullable=true)
     */
    protected $permissionOwnEvents;

    /**
     * @var boolean $permissionOwnComments
     *
     * @ORM\Column(name="permissionOwnComments", type="boolean", nullable=true)
     */
    protected $permissionOwnComments;

    /**
     * @var boolean $permissionForeignHomeworks
     *
     * @ORM\Column(name="permissionForeignHomeworks", type="boolean", nullable=true)
     */
    protected $permissionForeignHomeworks;

    /**
     * @var boolean $permissionForeignBlogs
     *
     * @ORM\Column(name="permissionForeignBlogs", type="boolean", nullable=true)
     */
    protected $permissionForeignBlogs;

    /**
     * @var boolean $permissionForeignSummaries
     *
     * @ORM\Column(name="permissionForeignSummaries", type="boolean", nullable=true)
     */
    protected $permissionForeignSummaries;

    /**
     * @var boolean $permissionForeignEvents
     *
     * @ORM\Column(name="permissionForeignEvents", type="boolean", nullable=true)
     */
    protected $permissionForeignEvents;

    /**
     * @var boolean $permissionForeignComments
     *
     * @ORM\Column(name="permissionForeignComments", type="boolean", nullable=true)
     */
    protected $permissionForeignComments;

    /**
     * @var boolean $permissionSchedule
     *
     * @ORM\Column(name="permissionSchedule", type="boolean", nullable=true)
     */
    protected $permissionSchedule;

    /**
     * @var boolean $permissionSubjects
     *
     * @ORM\Column(name="permissionSubjects", type="boolean", nullable=true)
     */
    protected $permissionSubjects;

    /**
     * @var boolean $permissionInvite
     *
     * @ORM\Column(name="permissionInvite", type="boolean", nullable=true)
     */
    protected $permissionInvite;

    /**
     * @var boolean $permissionAccept
     *
     * @ORM\Column(name="permissionAccept", type="boolean", nullable=true)
     */
    protected $permissionAccept;

    /**
     * @var boolean $permissionKickOut
     *
     * @ORM\Column(name="permissionKickOut", type="boolean", nullable=true)
     */
    protected $permissionKickOut;

    /**
     * @var boolean $permissionShare
     *
     * @ORM\Column(name="permissionShare", type="boolean", nullable=true)
     */
    protected $permissionShare;

    /**
     * @ORM\Column(type="datetime", name="lastChatActivity", nullable=true)
     *
     * @var DateTime $lastChatActivity
     */
    protected $lastChatActivity;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->educationGroup->getTitle();
    }

    public function __construct()
    {

    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public function serializePermissions() {

        return
            $this->permissionOwnHomeworks .
            $this->permissionOwnBlogs .
            $this->permissionOwnSummaries .
            $this->permissionOwnEvents .
            $this->permissionOwnComments .
            $this->permissionForeignHomeworks .
            $this->permissionForeignBlogs .
            $this->permissionForeignSummaries .
            $this->permissionForeignEvents .
            $this->permissionForeignComments .
            $this->permissionEmails .
            $this->permissionSchedule .
            $this->permissionSubjects .
            $this->permissionInvite .
            $this->permissionAccept .
            $this->permissionKickOut .
            $this->permissionShare;

    }

    public function setUserPermissions() {

        $this->permissionOwnHomeworks = true;
        $this->permissionOwnBlogs = true;
        $this->permissionOwnSummaries = true;
        $this->permissionOwnEvents = true;
        $this->permissionOwnComments = true;
        $this->permissionSchedule = true;
        $this->permissionSubjects = true;
        $this->permissionInvite = true;

    }

    public function setModeratorPermissions() {

        $this->permissionOwnHomeworks = true;
        $this->permissionOwnBlogs = true;
        $this->permissionOwnSummaries = true;
        $this->permissionOwnEvents = true;
        $this->permissionOwnComments = true;
        $this->permissionForeignHomeworks = true;
        $this->permissionForeignBlogs = true;
        $this->permissionForeignSummaries = true;
        $this->permissionForeignEvents = true;
        $this->permissionForeignComments = true;
        $this->permissionEmails = true;
        $this->permissionSchedule = true;
        $this->permissionSubjects = true;
        $this->permissionInvite = true;
        $this->permissionAccept = true;

    }

    public function setAdminPermissions() {

        $this->permissionOwnHomeworks = true;
        $this->permissionOwnBlogs = true;
        $this->permissionOwnSummaries = true;
        $this->permissionOwnEvents = true;
        $this->permissionOwnComments = true;
        $this->permissionForeignHomeworks = true;
        $this->permissionForeignBlogs = true;
        $this->permissionForeignSummaries = true;
        $this->permissionForeignEvents = true;
        $this->permissionForeignComments = true;
        $this->permissionEmails = true;
        $this->permissionSchedule = true;
        $this->permissionSubjects = true;
        $this->permissionInvite = true;
        $this->permissionAccept = true;
        $this->permissionKickOut = true;
        $this->permissionShare = true;

    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set creator
     *
     * @param boolean $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    /**
     * Get creator
     *
     * @return boolean 
     */
    public function getCreator()
    {
        return $this->creator;
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
     * Set permissionOwnHomeworks
     *
     * @param boolean $permissionOwnHomeworks
     */
    public function setPermissionOwnHomeworks($permissionOwnHomeworks)
    {
        $this->permissionOwnHomeworks = $permissionOwnHomeworks;
    }

    /**
     * Get permissionOwnHomeworks
     *
     * @return boolean 
     */
    public function getPermissionOwnHomeworks()
    {
        return $this->permissionOwnHomeworks;
    }

    /**
     * Set permissionOwnBlogs
     *
     * @param boolean $permissionOwnBlogs
     */
    public function setPermissionOwnBlogs($permissionOwnBlogs)
    {
        $this->permissionOwnBlogs = $permissionOwnBlogs;
    }

    /**
     * Get permissionOwnBlogs
     *
     * @return boolean 
     */
    public function getPermissionOwnBlogs()
    {
        return $this->permissionOwnBlogs;
    }

    /**
     * Set permissionOwnSummaries
     *
     * @param boolean $permissionOwnSummaries
     */
    public function setPermissionOwnSummaries($permissionOwnSummaries)
    {
        $this->permissionOwnSummaries = $permissionOwnSummaries;
    }

    /**
     * Get permissionOwnSummaries
     *
     * @return boolean 
     */
    public function getPermissionOwnSummaries()
    {
        return $this->permissionOwnSummaries;
    }

    /**
     * Set permissionOwnEvents
     *
     * @param boolean $permissionOwnEvents
     */
    public function setPermissionOwnEvents($permissionOwnEvents)
    {
        $this->permissionOwnEvents = $permissionOwnEvents;
    }

    /**
     * Get permissionOwnEvents
     *
     * @return boolean 
     */
    public function getPermissionOwnEvents()
    {
        return $this->permissionOwnEvents;
    }

    /**
     * Set permissionOwnComments
     *
     * @param boolean $permissionOwnComments
     */
    public function setPermissionOwnComments($permissionOwnComments)
    {
        $this->permissionOwnComments = $permissionOwnComments;
    }

    /**
     * Get permissionOwnComments
     *
     * @return boolean 
     */
    public function getPermissionOwnComments()
    {
        return $this->permissionOwnComments;
    }

    /**
     * Set permissionForeignHomeworks
     *
     * @param boolean $permissionForeignHomeworks
     */
    public function setPermissionForeignHomeworks($permissionForeignHomeworks)
    {
        $this->permissionForeignHomeworks = $permissionForeignHomeworks;
    }

    /**
     * Get permissionForeignHomeworks
     *
     * @return boolean 
     */
    public function getPermissionForeignHomeworks()
    {
        return $this->permissionForeignHomeworks;
    }

    /**
     * Set permissionForeignBlogs
     *
     * @param boolean $permissionForeignBlogs
     */
    public function setPermissionForeignBlogs($permissionForeignBlogs)
    {
        $this->permissionForeignBlogs = $permissionForeignBlogs;
    }

    /**
     * Get permissionForeignBlogs
     *
     * @return boolean 
     */
    public function getPermissionForeignBlogs()
    {
        return $this->permissionForeignBlogs;
    }

    /**
     * Set permissionForeignSummaries
     *
     * @param boolean $permissionForeignSummaries
     */
    public function setPermissionForeignSummaries($permissionForeignSummaries)
    {
        $this->permissionForeignSummaries = $permissionForeignSummaries;
    }

    /**
     * Get permissionForeignSummaries
     *
     * @return boolean 
     */
    public function getPermissionForeignSummaries()
    {
        return $this->permissionForeignSummaries;
    }

    /**
     * Set permissionForeignEvents
     *
     * @param boolean $permissionForeignEvents
     */
    public function setPermissionForeignEvents($permissionForeignEvents)
    {
        $this->permissionForeignEvents = $permissionForeignEvents;
    }

    /**
     * Get permissionForeignEvents
     *
     * @return boolean 
     */
    public function getPermissionForeignEvents()
    {
        return $this->permissionForeignEvents;
    }

    /**
     * Set permissionForeignComments
     *
     * @param boolean $permissionForeignComments
     */
    public function setPermissionForeignComments($permissionForeignComments)
    {
        $this->permissionForeignComments = $permissionForeignComments;
    }

    /**
     * Get permissionForeignComments
     *
     * @return boolean 
     */
    public function getPermissionForeignComments()
    {
        return $this->permissionForeignComments;
    }

    /**
     * Set permissionSubjects
     *
     * @param boolean $permissionSubjects
     */
    public function setPermissionSubjects($permissionSubjects)
    {
        $this->permissionSubjects = $permissionSubjects;
    }

    /**
     * Get permissionSubjects
     *
     * @return boolean 
     */
    public function getPermissionSubjects()
    {
        return $this->permissionSubjects;
    }

    /**
     * Set permissionInvite
     *
     * @param boolean $permissionInvite
     */
    public function setPermissionInvite($permissionInvite)
    {
        $this->permissionInvite = $permissionInvite;
    }

    /**
     * Get permissionInvite
     *
     * @return boolean 
     */
    public function getPermissionInvite()
    {
        return $this->permissionInvite;
    }

    /**
     * Set permissionAccept
     *
     * @param boolean $permissionAccept
     */
    public function setPermissionAccept($permissionAccept)
    {
        $this->permissionAccept = $permissionAccept;
    }

    /**
     * Get permissionAccept
     *
     * @return boolean 
     */
    public function getPermissionAccept()
    {
        return $this->permissionAccept;
    }

    /**
     * Set permissionKickOut
     *
     * @param boolean $permissionKickOut
     */
    public function setPermissionKickOut($permissionKickOut)
    {
        $this->permissionKickOut = $permissionKickOut;
    }

    /**
     * Get permissionKickOut
     *
     * @return boolean 
     */
    public function getPermissionKickOut()
    {
        return $this->permissionKickOut;
    }

    /**
     * Set permissionShare
     *
     * @param boolean $permissionShare
     */
    public function setPermissionShare($permissionShare)
    {
        $this->permissionShare = $permissionShare;
    }

    /**
     * Get permissionShare
     *
     * @return boolean 
     */
    public function getPermissionShare()
    {
        return $this->permissionShare;
    }

    /**
     * Set permissionSchedule
     *
     * @param boolean $permissionSchedule
     */
    public function setPermissionSchedule($permissionSchedule)
    {
        $this->permissionSchedule = $permissionSchedule;
    }

    /**
     * Get permissionSchedule
     *
     * @return boolean 
     */
    public function getPermissionSchedule()
    {
        return $this->permissionSchedule;
    }

    /**
     * Set invitePromoSeen
     *
     * @param boolean $invitePromoSeen
     */
    public function setInvitePromoSeen($invitePromoSeen)
    {
        $this->invitePromoSeen = $invitePromoSeen;
    }

    /**
     * Get invitePromoSeen
     *
     * @return boolean 
     */
    public function getInvitePromoSeen()
    {
        return $this->invitePromoSeen;
    }

    /**
     * Set lastChatActivity
     *
     * @param datetime $lastChatActivity
     */
    public function setLastChatActivity($lastChatActivity)
    {
        $this->lastChatActivity = $lastChatActivity;
    }

    /**
     * Get lastChatActivity
     *
     * @return datetime 
     */
    public function getLastChatActivity()
    {
        return $this->lastChatActivity;
    }

    /**
     * Set inviteWidgetHidden
     *
     * @param boolean $inviteWidgetHidden
     */
    public function setInviteWidgetHidden($inviteWidgetHidden)
    {
        $this->inviteWidgetHidden = $inviteWidgetHidden;
    }

    /**
     * Get inviteWidgetHidden
     *
     * @return boolean 
     */
    public function getInviteWidgetHidden()
    {
        return $this->inviteWidgetHidden;
    }

    /**
     * Set permissionEmails
     *
     * @param boolean $permissionEmails
     */
    public function setPermissionEmails($permissionEmails)
    {
        $this->permissionEmails = $permissionEmails;
    }

    /**
     * Get permissionEmails
     *
     * @return boolean 
     */
    public function getPermissionEmails()
    {
        return $this->permissionEmails;
    }

    /**
     * Set defaultScheduleAlertHidden
     *
     * @param boolean $defaultScheduleAlertHidden
     * @return UserEducationGroup
     */
    public function setDefaultScheduleAlertHidden($defaultScheduleAlertHidden)
    {
        $this->defaultScheduleAlertHidden = $defaultScheduleAlertHidden;
        return $this;
    }

    /**
     * Get defaultScheduleAlertHidden
     *
     * @return boolean 
     */
    public function getDefaultScheduleAlertHidden()
    {
        return $this->defaultScheduleAlertHidden;
    }
}