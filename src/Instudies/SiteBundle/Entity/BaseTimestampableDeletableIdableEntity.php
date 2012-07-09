<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class BaseTimestampableDeletableIdableEntity extends BaseEntity implements BaseTimestampableEntityInterface, BaseDeletableEntityInterface, BaseIdableEntityInterface
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

    /**
     * @ORM\Column(name="deleted", type="boolean")
     */
    protected $deleted;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    /**
     * @ORM\prePersist
     */
    public function prePersistDeletableCallback()
    {
        if ($this->deleted == null) {
            $this->deleted = false;
        }
    }

    /**
     * @ORM\preUpdate
     */
    public function preUpdateDeletableCallback()
    {
        if ($this->deleted == null) {
            $this->deleted = false;
        }
    }

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

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    public function getDeleted()
    {
        return $this->deleted;
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