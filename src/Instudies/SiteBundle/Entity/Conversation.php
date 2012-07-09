<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection,
    Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert,
    Symfony\Component\Validator\Constraints as Assert
;

/**
 * Instudies\SiteBundle\Entity\Conversation
 *
 * @ORM\Table(name="Conversation")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Instudies\SiteBundle\Entity\ConversationRepository")
 */
class Conversation extends BaseTimestampableIdableEntity
{

    /**********************************************************************************
     * FIELDS
     *********************************************************************************/

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="conversations1")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user1;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="conversations2")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $user2;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="conversation")
     */
    protected $messages;

    public $lastMessage;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->text;
    }

    public function __construct()
    {

    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/


    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    /**
     * Set lastMessageCreated
     *
     * @param datetime $lastMessageCreated
     */
    public function setLastMessageCreated($lastMessageCreated)
    {
        $this->lastMessageCreated = $lastMessageCreated;
    }

    /**
     * Get lastMessageCreated
     *
     * @return datetime 
     */
    public function getLastMessageCreated()
    {
        return $this->lastMessageCreated;
    }

    /**
     * Set user1
     *
     * @param Instudies\SiteBundle\Entity\User $user1
     */
    public function setUser1(\Instudies\SiteBundle\Entity\User $user1)
    {
        $this->user1 = $user1;
    }

    /**
     * Get user1
     *
     * @return Instudies\SiteBundle\Entity\User 
     */
    public function getUser1()
    {
        return $this->user1;
    }

    /**
     * Set user2
     *
     * @param Instudies\SiteBundle\Entity\User $user2
     */
    public function setUser2(\Instudies\SiteBundle\Entity\User $user2)
    {
        $this->user2 = $user2;
    }

    /**
     * Get user2
     *
     * @return Instudies\SiteBundle\Entity\User 
     */
    public function getUser2()
    {
        return $this->user2;
    }



    /**
     * Add messages
     *
     * @param Instudies\SiteBundle\Entity\Message $messages
     */
    public function addMessage(\Instudies\SiteBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;
    }

    /**
     * Get messages
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}