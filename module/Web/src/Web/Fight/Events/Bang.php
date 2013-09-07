<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Bang
{
    /** @var \Web\Document\PlayerStat */
    private $attacker;

    function __invoke(Event $evt)
    {
        if ($evt->getAction()->attributes->getNamedItem('class')->textContent == 'bang-throw ') {
            $this->attacker = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(0)->textContent));
            $this->attacker->incrementThrows();

            $evt->stopPropagation();
            return;
        }

        if ($this->attacker && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'bang ') {
            $victim = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(1)->textContent));

            if ($this->attacker->getTeam() != $victim->getTeam()) {
                $this->attacker->incrementDamage($evt->clearDamage($evt->getAction()->lastChild->textContent));
            }

            $evt->stopPropagation();
            return;
        }
    }
}