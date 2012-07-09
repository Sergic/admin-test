<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
abstract class BaseDelatableEntity extends BaseEntity implements BaseDeletableEntityInterface
{

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
    public function setDeletedValue()
    {
        if ($this->deleted == null) {
            $this->deleted = false;
        }
    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

}