<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroupSubjectTopic
 *
 * @ORM\Table(name="EducationGroupSubjectTopic")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupSubjectTopicRepository")
 */
class EducationGroupSubjectTopic extends BaseTimestampableIdableEntity
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
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @var integer $sort
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    protected $sort;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="educationGroupSubjectTopic")
     */
    protected $activities;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSubject", inversedBy="topics")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $subject;

    public $isReaded;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->title;
    }

    public function __construct()
    {
        $this->readed = new ArrayCollection();
        $this->activities = new ArrayCollection();
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
     * Set date
     *
     * @param datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return datetime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set subject
     *
     * @param Instudies\SiteBundle\Entity\EducationGroupSubject $subject
     */
    public function setSubject(\Instudies\SiteBundle\Entity\EducationGroupSubject $subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get subject
     *
     * @return Instudies\SiteBundle\Entity\EducationGroupSubject 
     */
    public function getSubject()
    {
        return $this->subject;
    }



    /**
     * Add activities
     *
     * @param Instudies\SiteBundle\Entity\Activity $activities
     */
    public function addActivity(\Instudies\SiteBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;
    }

    /**
     * Get activities
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getActivities()
    {
        return $this->activities;
    }
}