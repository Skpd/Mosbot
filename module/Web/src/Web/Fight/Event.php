<?php

namespace Web\Fight;

use Web\Document\PlayerStat;
use Web\Fight\Exception\PlayerNotFoundException;
use Zend\Dom\Query;
use Zend\EventManager\Event as BaseEvent;
use \DomNode;

class Event extends BaseEvent
{
    /** @var \Web\Document\FightResult */
    private $result;

    /** @var DomNode */
    private $action;

    /** @var Query */
    private $query;

    /**
     * @param \Web\Document\FightResult $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return \Web\Document\FightResult
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param DomNode $action
     */
    public function setAction(DomNode $action)
    {
        $this->action = $action;
    }

    /**
     * @return DomNode
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param \Zend\Dom\Query $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return \Zend\Dom\Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Removes brackets, spaces and minus sign from damage.
     * @param $str
     * @return int
     */
    public function clearDamage($str)
    {
        return intval(preg_replace('/[^\d]/', '', $str));
    }

    /**
     * Removes space and level from nickname.
     * @param $str
     * @return string
     */
    public function clearNickname($str)
    {
        $str     = str_replace(['&nbsp;', chr(194).chr(160)], '', $str);
        $bracket = strpos($str, '[');

        if ($bracket !== false) {
            $str = substr($str, 0, strlen($str) - (strlen($str) - $bracket));
            $str = preg_replace('/\s+$/', '', $str);
        }

        return $str;
    }

    /**
     * @param $nickname
     * @return PlayerStat
     * @throws PlayerNotFoundException
     */
    public function getPlayerByNickname($nickname)
    {
        foreach ($this->getResult()->getPlayers() as $player) {
            /** @var PlayerStat $player */
            if ($player->getPlayer()->getNickname() == $nickname) {
                return $player;
            }
        }

        throw new PlayerNotFoundException(sprintf(
            "Player '$nickname' not found. Players list: %s",
            implode(', ', array_map(function ($player) {
                return $player->getPlayer()->getNickname();
            }, $this->getResult()->getPlayers()->toArray()))
        ));
    }
}