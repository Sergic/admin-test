<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\EducationGroupSchedule
 *
 * @ORM\Table(name="EducationGroupSchedule")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\EducationGroupScheduleRepository")
 * @DoctrineAssert\UniqueEntity(fields="slug", message="Group slug should be unique")
 */
class EducationGroupSchedule extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @var datetime $weekDay
     *
     * @ORM\Column(name="weekDay", type="integer", nullable=true)
     */
    protected $weekDay;

    /**
     * @var datetime $weekNum
     *
     * @ORM\Column(name="weekNum", type="integer", nullable=true)
     */
    protected $weekNum;

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
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var string $place
     *
     * @ORM\Column(name="place", type="string", length=255, nullable=true)
     */
    protected $place;

    /**
     * @var string $repeat
     *
     * @ORM\Column(name="repeatType", type="string", length=255, nullable=true)
     */
    protected $repeat;

    /**
     * @ORM\ManyToOne(targetEntity="EducationGroupSubject", inversedBy="schedules")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $subject;

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

    public $isDefaultSchedule;

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

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
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set place
     *
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * Get place
     *
     * @return string 
     */
    public function getPlace()
    {
        return $this->place;
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
     * Set weekDay
     *
     * @param string $weekDay
     */
    public function setWeekDay($weekDay)
    {
        $this->weekDay = $weekDay;
    }

    /**
     * Get weekDay
     *
     * @return string 
     */
    public function getWeekDay()
    {
        return $this->weekDay;
    }

    /**
     * Set repeat
     *
     * @param string $repeat
     */
    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;
    }

    /**
     * Get repeat
     *
     * @return string 
     */
    public function getRepeat()
    {
        return $this->repeat;
    }

    /**
     * Set weekDayNum
     *
     * @param integer $weekDayNum
     */
    public function setWeekDayNum($weekDayNum)
    {
        $this->weekDayNum = $weekDayNum;
    }

    /**
     * Get weekDayNum
     *
     * @return integer 
     */
    public function getWeekDayNum()
    {
        return $this->weekDayNum;
    }

    /**
     * Set weekNum
     *
     * @param integer $weekNum
     */
    public function setWeekNum($weekNum)
    {
        $this->weekNum = $weekNum;
    }

    /**
     * Get weekNum
     *
     * @return integer 
     */
    public function getWeekNum()
    {
        return $this->weekNum;
    }
}