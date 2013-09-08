<?php

namespace Web\Fight\Events;

use Web\Document\PlayerStat;
use Web\Fight\Event;

class Winner
{
    public function __invoke(Event $evt)
    {
        $sum = [PlayerStat::TEAM_LEFT => 0, PlayerStat::TEAM_RIGHT => 0];

        foreach ($evt->getResult()->getPlayers() as $player) {
            /** @var PlayerStat $player */
            $sum[$player->getTeam()] += $player->getDamage();
        }

        $evt->getResult()->setWinner(
            $sum[PlayerStat::TEAM_LEFT] > $sum[PlayerStat::TEAM_RIGHT] ? PlayerStat::TEAM_LEFT : PlayerStat::TEAM_RIGHT
        );
    }
}