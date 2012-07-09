<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\UnreadedEducationGroupEventPost
 *
 * @ORM\Table(name="UnreadedEducationGroupEventPost")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\UnreadedEducationGroupEventPostRepository")
 */
class UnreadedEducationGroupEventPost extends BaseTimestampableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="unreadedEducationGroupEventPost")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EducationGroupEventPost", inversedBy="unreaded")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupEventPost;

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

}