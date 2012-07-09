<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\UnreadedEducationGroupHomeworkPost
 *
 * @ORM\Table(name="UnreadedEducationGroupHomeworkPost")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\UnreadedEducationGroupHomeworkPostRepository")
 */
class UnreadedEducationGroupHomeworkPost extends BaseTimestampableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="unreadedEducationGroupHomeworkPost")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EducationGroupHomeworkPost", inversedBy="unreaded")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupHomeworkPost;

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