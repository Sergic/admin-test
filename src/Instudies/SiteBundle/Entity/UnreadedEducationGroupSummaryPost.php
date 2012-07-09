<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\UnreadedEducationGroupSummaryPost
 *
 * @ORM\Table(name="UnreadedEducationGroupSummaryPost")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\UnreadedEducationGroupSummaryPostRepository")
 */
class UnreadedEducationGroupSummaryPost extends BaseTimestampableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="unreadedEducationGroupSummaryPost")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EducationGroupSummaryPost", inversedBy="unreaded")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupSummaryPost;

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