<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class BaseTimestampableIdableEntity extends BaseEntity implements BaseTimestampableEntityInterface, BaseIdableEntityInterface
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="created", type="datetime")
     */
	protected $created;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
	protected $updated;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    /**
     * @ORM\prePersist
     */
    public function prePersistTimestampableCallback()
    {
        if ($this->created == null) {
            $this->created = new \DateTime();
        }
    }

    /**
     * @ORM\preUpdate
     */
    public function preUpdateTimestampableCallback()
    {
        $this->updated = new \DateTime();
    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

}