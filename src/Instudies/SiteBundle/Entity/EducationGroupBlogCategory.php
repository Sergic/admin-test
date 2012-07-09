<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroupBlogCategory
 *
 * @ORM\Table(name="EducationGroupBlogCategory")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupBlogCategoryRepository")
 * @DoctrineAssert\UniqueEntity(fields="slug", message="Group slug should be unique")
 */
class EducationGroupBlogCategory extends BaseTimestampableIdableEntity
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
     * @var boolean $educationGroupAssociated
     *
     * @ORM\Column(name="educationGroupAssociated", type="boolean", nullable=true)
     */
    protected $educationGroupAssociated;

    /**
     * @ORM\OneToMany(targetEntity="EducationGroupBlogPost", mappedBy="category")
     */
    protected $blogPosts;

    public $blogPostsCount;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->title;
    }

    public function __construct()
    {
        $this->educationGroupBlogPosts = new ArrayCollection();
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
     * Add blogPosts
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupBlogPost $blogPosts
     */
    public function addEducationGroupBlogPost(\Instudies\SiteBundle\Entity\EducationGroupBlogPost $blogPosts)
    {
        $this->blogPosts[] = $blogPosts;
    }

    /**
     * Get blogPosts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBlogPosts()
    {
        return $this->blogPosts;
    }

    /**
     * Set educationGroupAssociated
     *
     * @param boolean $educationGroupAssociated
     */
    public function setEducationGroupAssociated($educationGroupAssociated)
    {
        $this->educationGroupAssociated = $educationGroupAssociated;
    }

    /**
     * Get educationGroupAssociated
     *
     * @return boolean 
     */
    public function getEducationGroupAssociated()
    {
        return $this->educationGroupAssociated;
    }
}