<?php

namespace Web\Fight\Events;

use Web\Fight\Event;
use Web\Fight\Exception\PlayerNotFoundException;

class Kill
{
    function __invoke(Event $evt)
    {
        if ($evt->getAction()->attributes && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'killed ') {
            try {
                $attacker = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(0)->textContent));
                $attacker->incrementKills();
            } catch (PlayerNotFoundException $e) {
                // pet kill, do nothing
            }

            $evt->stopPropagation();
        }
    }
}