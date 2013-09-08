<?php

namespace Web\Document;

use \Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Class PlayerStat
 * @package Web\Document
 * @ODM\EmbeddedDocument
 */
class PlayerStat
{
    const TEAM_LEFT = 1;
    const TEAM_RIGHT = 2;

    /**
     * @var \Runner\Document\Player
     * @ODM\EmbedOne(targetDocument="Runner\Document\Player")
     */
    private $player;

    /**
     * @var int
     * @ODM\Field(type="int")
     */
    private $damage = 0;

    /**
     * @var float
     * @ODM\Field(type="float")
     */
    private $percentage = 0;

    /**
     * @var int
     * @ODM\Field(type="int")
     */
    private $kills = 0;

    /**
     * @var int
     * @ODM\Field(type="int")
     */
    private $healed = 0;

    /**
     * @var int
     * @ODM\Field(type="int")
     */
    private $team = 0;

    /**
     * @var int
     * @ODM\Field(type="int")
     */
    private $throws;

    /**
     * @param int $damage
     */
    public function setDamage($damage)
    {
        $this->damage = $damage;
    }

    /**
     * @return int
     */
    public function getDamage()
    {
        return $this->damage;
    }

    public function incrementDamage($damage)
    {
        $this->damage += $damage;
    }

    /**
     * @param int $kills
     */
    public function setKills($kills)
    {
        $this->kills = $kills;
    }

    /**
     * @return int
     */
    public function getKills()
    {
        return $this->kills;
    }

    public function incrementKills()
    {
        $this->kills++;
    }

    /**
     * @param \Runner\Document\Player $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * @return \Runner\Document\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param int $healed
     */
    public function setHealed($healed)
    {
        $this->healed = $healed;
    }

    /**
     * @return int
     */
    public function getHealed()
    {
        return $this->healed;
    }

    public function incrementHealed($healed)
    {
        $this->healed += $healed;
    }

    /**
     * @param int $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return int
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param int $throws
     */
    public function setThrows($throws)
    {
        $this->throws = $throws;
    }

    /**
     * @return int
     */
    public function getThrows()
    {
        return $this->throws;
    }

    public function incrementThrows()
    {
        $this->throws++;
    }

    /**
     * @param float $percentage
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * @return float
     */
    public function getPercentage()
    {
        return $this->percentage;
    }
}