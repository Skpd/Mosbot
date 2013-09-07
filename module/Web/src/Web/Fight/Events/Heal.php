<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Heal
{
    function __invoke(Event $evt)
    {
        if ($evt->getAction()->attributes && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'heal ') {
            $attacker = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(1)->textContent));
            $heal     = $evt->clearDamage($evt->getAction()->lastChild->textContent);

            $attacker->incrementHealed($heal);

            $evt->stopPropagation();
        }
    }
}