<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroupEventCategory
 *
 * @ORM\Table(name="EducationGroupEventCategory")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupEventCategoryRepository")
 * @DoctrineAssert\UniqueEntity(fields="slug", message="Group slug should be unique")
 */
class EducationGroupEventCategory extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupEventPost", mappedBy="category")
     */
    protected $eventPosts;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->title;
    }

    public function __construct()
    {
        $this->educationGroupEventPosts = new ArrayCollection();
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public $actualPostsCount;

    public $archivedPostsCount;

    public function totalPostsCount() {
        return $actualPostsCount + $archivedPostsCount;
    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add eventPosts
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupEventPost $eventPosts
     */
    public function addEducationGroupEventPost(\Instudies\SiteBundle\Entity\EducationGroupEventPost $eventPosts)
    {
        $this->eventPosts[] = $eventPosts;
    }

    /**
     * Get eventPosts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEventPosts()
    {
        return $this->eventPosts;
    }
}