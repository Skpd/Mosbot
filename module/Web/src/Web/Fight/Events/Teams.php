<?php

namespace Web\Fight\Events;

use Runner\Document\Player;
use Web\Document\PlayerStat;
use Web\Fight\Event;

class Teams
{
    function __invoke(Event $e)
    {
        foreach ([PlayerStat::TEAM_LEFT, PlayerStat::TEAM_RIGHT] as $teamNumber) {
            $items = $e->getQuery()->queryXpath(
                "descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' group ')][" . $teamNumber
                . "]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' list-users ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' user ')]/descendant::a[last()]"
            );

            foreach ($items as $item) {
                $playerID = intval(preg_replace('/[^\d]/', '', $item->attributes->getNamedItem('href')->textContent));

                if (!$e->getResult()->getPlayers()->exists(
                    function ($key, $element) use ($playerID) {
                        return $element->getPlayer() && $element->getPlayer()->getId() == $playerID;
                    }))
                {
                    $player = new Player();
                    $player->setId($playerID);
                    $player->setNickname($item->textContent);
                    $player->setState(null);
                    $player->setClan(null);

                    $stat = new PlayerStat();
                    $stat->setPlayer($player);
                    $stat->setTeam($teamNumber);

                    $e->getResult()->getPlayers()->add($stat);
                }
            }
        }
    }
}