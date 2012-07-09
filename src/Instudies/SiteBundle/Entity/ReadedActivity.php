<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\ReadedActivity
 *
 * @ORM\Table(name="ReadedActivity")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\ReadedActivityRepository")
 */
class ReadedActivity extends BaseTimestampableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="readedActivity")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="readed")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $activity;

    /**
     * @var boolean $userNotified
     *
     * @ORM\Column(name="userNotified", type="boolean", nullable=true)
     */
    protected $userNotified;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return '';
    }

    public function __construct()
    {

    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set userNotified
     *
     * @param boolean $userNotified
     */
    public function setUserNotified($userNotified)
    {
        $this->userNotified = $userNotified;
    }

    /**
     * Get userNotified
     *
     * @return boolean 
     */
    public function getUserNotified()
    {
        return $this->userNotified;
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
     * Set activity
     *
     * @param Instudies\SiteBundle\Entity\Activity $activity
     */
    public function setActivity(\Instudies\SiteBundle\Entity\Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * Get activity
     *
     * @return Instudies\SiteBundle\Entity\Activity 
     */
    public function getActivity()
    {
        return $this->activity;
    }
}