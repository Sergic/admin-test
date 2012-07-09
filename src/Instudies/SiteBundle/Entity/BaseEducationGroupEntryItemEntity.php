<?php

namespace Instudies\SiteBundle\Entity;

use
    Doctrine\ORM\Mapping as ORM
;

/**
 * @ORM\MappedSuperclass
 */
abstract class BaseEducationGroupEntryItemEntity extends BaseTimestampableDeletableIdableEntity
{

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(name="text", type="text")
     */
    protected $text;

    /**********************************************************************************
     * CONSTRUCT
     *********************************************************************************/

    public function __toString()
    {
        return $this->title;
    }

    /**********************************************************************************
     * HELPERS
     *********************************************************************************/

    public $isReaded;

    public $creationActivityIsReaded;

    public $inMyFavorites;

    public $commentsNum;

    public $commentsNewNum;

    private $textParts;

    public function cutedText()
    {
        $this->textParts = explode('<cut>', $this->text);
        return $this->textParts[0];
    }

    public function hasContinue()
    {
        if (count($this->textParts) > 1) {
            return true;
        }
        return false;
    }

    /**********************************************************************************
     * GETTERS & SETTERS
     *********************************************************************************/

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

}