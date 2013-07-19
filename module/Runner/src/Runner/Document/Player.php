<?php

namespace Runner\Document;

use \Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Player
 * @ODM\Document(db="mosbot", collection="players")
 *
 * @package Runner\Document
 */
class Player
{
    /** @ODM\Id(strategy="NONE", type="int") */
    private $id;

    /** @ODM\Field(type="int") */
    private $level;
    /** @ODM\Field(type="string") */
    private $alignment;
    /** @ODM\Field(type="string") */
    private $nickname;

    /** @ODM\Field(type="int") */
    private $maxLife;
    /** @ODM\Field(type="int") */
    private $wins;
    /** @ODM\Field(type="int") */
    private $loot;

    /** @ODM\Field(type="int") */
    private $health;
    /** @ODM\Field(type="int") */
    private $strength;
    /** @ODM\Field(type="int") */
    private $dexterity;
    /** @ODM\Field(type="int") */
    private $resistance;
    /** @ODM\Field(type="int") */
    private $intuition;
    /** @ODM\Field(type="int") */
    private $attention;
    /** @ODM\Field(type="int") */
    private $charism;

    /** @ODM\Field(type="int") */
    private $haveRocket;

    /** @ODM\Field(type="hash") */
    private $items;

    /** @ODM\Field(type="int") */
    private $coolness;

    /** @ODM\Field(type="date") */
    private $lastUpdate;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /** @ODM\PrePersist */
    public function doStuffOnPrePersist()
    {
        $this->lastUpdate = new \DateTime();
        $this->coolness   = $this->health + $this->strength + $this->dexterity + $this->resistance + $this->intuition + $this->attention + $this->charism;
    }

    /**
     * @param mixed $haveRocket
     */
    public function setHaveRocket($haveRocket)
    {
        $this->haveRocket = $haveRocket;
    }

    /**
     * @return mixed
     */
    public function getHaveRocket()
    {
        return $this->haveRocket;
    }

    /**
     * @param mixed $alignment
     */
    public function setAlignment($alignment)
    {
        $this->alignment = $alignment;
    }

    /**
     * @return mixed
     */
    public function getAlignment()
    {
        return $this->alignment;
    }

    /**
     * @param mixed $attention
     */
    public function setAttention($attention)
    {
        $this->attention = $attention;
    }

    /**
     * @return mixed
     */
    public function getAttention()
    {
        return $this->attention;
    }

    /**
     * @param mixed $charism
     */
    public function setCharism($charism)
    {
        $this->charism = $charism;
    }

    /**
     * @return mixed
     */
    public function getCharism()
    {
        return $this->charism;
    }

    /**
     * @param mixed $dexterity
     */
    public function setDexterity($dexterity)
    {
        $this->dexterity = $dexterity;
    }

    /**
     * @return mixed
     */
    public function getDexterity()
    {
        return $this->dexterity;
    }

    /**
     * @param mixed $health
     */
    public function setHealth($health)
    {
        $this->health = $health;
    }

    /**
     * @return mixed
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $intuition
     */
    public function setIntuition($intuition)
    {
        $this->intuition = $intuition;
    }

    /**
     * @return mixed
     */
    public function getIntuition()
    {
        return $this->intuition;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $loot
     */
    public function setLoot($loot)
    {
        $this->loot = $loot;
    }

    /**
     * @return mixed
     */
    public function getLoot()
    {
        return $this->loot;
    }

    /**
     * @param mixed $maxLife
     */
    public function setMaxLife($maxLife)
    {
        $this->maxLife = $maxLife;
    }

    /**
     * @return mixed
     */
    public function getMaxLife()
    {
        return $this->maxLife;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param mixed $resistance
     */
    public function setResistance($resistance)
    {
        $this->resistance = $resistance;
    }

    /**
     * @return mixed
     */
    public function getResistance()
    {
        return $this->resistance;
    }

    /**
     * @param mixed $strength
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;
    }

    /**
     * @return mixed
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @param mixed $wins
     */
    public function setWins($wins)
    {
        $this->wins = $wins;
    }

    /**
     * @return mixed
     */
    public function getWins()
    {
        return $this->wins;
    }

    /**
     * @param ArrayCollection $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $coolness
     */
    public function setCoolness($coolness)
    {
        $this->coolness = $coolness;
    }

    /**
     * @return mixed
     */
    public function getCoolness()
    {
        return $this->coolness;
    }

    /**
     * @param mixed $lastUpdate
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @return mixed
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }


}