<?php

namespace Web\Document;

use \Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Class FightResult
 * @package Web\Document
 * @ODM\Document(db="mosbot", collection="fights")
 */
class FightResult
{
    /**
     * @var int
     * @ODM\Id(strategy="NONE", type="int")
     */
    private $id;

    /**
     * @var int
     * @ODM\Field(type="int")
     */
    private $winner = 0;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ODM\EmbedMany(targetDocument="PlayerStat")
     */
    private $players;

    /**
     * @var \Datetime
     * @ODM\Field(type="date")
     */
    private $date;

    function __construct()
    {
        $this->players = new ArrayCollection();
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $players
     */
    public function setPlayers($players)
    {
        $this->players = $players;
    }

    /**
     * @param null $team
     * @return ArrayCollection
     */
    public function getPlayers($team = null)
    {
        if ($team !== null) {
            return $this->players->filter(function ($player) use ($team) {
                return $player->getTeam() == $team;
            });
        }
        return $this->players;
    }

    /**
     * @param int $winner
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }

    /**
     * @return int
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param \Datetime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \Datetime
     */
    public function getDate()
    {
        return $this->date;
    }
}