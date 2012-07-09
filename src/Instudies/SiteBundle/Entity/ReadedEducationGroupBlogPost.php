<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\ReadedEducationGroupBlogPost
 *
 * @ORM\Table(name="ReadedEducationGroupBlogPost")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\ReadedEducationGroupBlogPostRepository")
 */
class ReadedEducationGroupBlogPost extends BaseTimestampableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="User", inversedBy="readedEducationGroupBlogPost")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="EducationGroupBlogPost", inversedBy="readed")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroupBlogPost;

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
}