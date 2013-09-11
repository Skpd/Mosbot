<?php

namespace Web\Fight\Events;

use Web\Fight\Event;
use Web\Fight\Exception\PlayerNotFoundException;

class Miss
{
    public function __invoke(Event $evt)
    {
        $item = $evt->getAction()->childNodes->item(1);

        if ($item && $item->attributes && $item->attributes->getNamedItem('class')->textContent != 'miss') {
            return;
        }

        try {
            $attacker = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(0)->textContent));
            $attacker->setMisses($attacker->getMisses() + 1);
        } catch (PlayerNotFoundException $e) {
            //pet miss, do nothing
        }

        $evt->stopPropagation();
        return __CLASS__;
    }
}