<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroupDefaultSchedule
 *
 * @ORM\Table(name="EducationGroupDefaultSchedule")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupDefaultScheduleRepository")
 * @DoctrineAssert\UniqueEntity(fields="slug", message="Group slug should be unique")
 */
class EducationGroupDefaultSchedule extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var time $time
     *
     * @ORM\Column(name="time", type="time", nullable=true)
     */
    protected $time;

    /**
     * @var endTime $endTime
     *
     * @ORM\Column(name="endTime", type="time", nullable=true)
     */
    protected $endTime;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroup", inversedBy="schedules")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $educationGroup;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->title;
    }

    public function __construct()
    {

    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public $schedule;

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set time
     *
     * @param time $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Get time
     *
     * @return time 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set endTime
     *
     * @param time $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * Get endTime
     *
     * @return time 
     */
    public function getEndTime()
    {
        return $this->endTime;
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
}