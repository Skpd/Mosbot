<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Kill
{
    function __invoke(Event $evt)
    {
        if ($evt->getAction()->attributes && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'killed ') {
            $attacker = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(0)->textContent));
            $attacker->incrementKills();

            $evt->stopPropagation();
        }
    }
}