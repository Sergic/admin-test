<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert,
    Symfony\Component\Security\Core\Role\RoleInterface
;

/**
 * @ORM\Entity
 * @ORM\Table(name="Role")
 * @ORM\HasLifecycleCallbacks()
 */
class Role extends BaseTimestampableIdableEntity implements RoleInterface
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string $name
     */
    protected $name;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {

    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * @return string The role.
     */
    public function getRole()
    {
        return $this->getName();
    }
 
    /**
     * @return string The name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $value The name.
     */
    public function setName($value)
    {
        $this->name = $value;
    }

}