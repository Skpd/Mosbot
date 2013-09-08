<?php

namespace Web\Fight\Events;

use Web\Document\PlayerStat;
use Web\Fight\Event;

class DamagePercentage
{
    public function __invoke(Event $evt)
    {
        $sum = [PlayerStat::TEAM_LEFT => 0, PlayerStat::TEAM_RIGHT => 0];

        foreach ($evt->getResult()->getPlayers() as $player) {
            /** @var PlayerStat $player */
            $sum[$player->getTeam()] += $player->getDamage();
        }

        foreach ($evt->getResult()->getPlayers() as $player) {
            /** @var PlayerStat $player */
            if ($sum[$player->getTeam()] == 0) {
                $player->setPercentage(0);
            } else {
                $player->setPercentage($player->getDamage() / $sum[$player->getTeam()] * 100);
            }
        }
    }
}