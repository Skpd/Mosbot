<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Miss
{
    public function __invoke(Event $e)
    {
        if ($e->getAction()->childNodes->length != 3) {
            return;
        }

        $item = $e->getAction()->childNodes->item(1);

        if ($item->attributes && $item->attributes->getNamedItem('class')->textContent != 'miss') {
            return;
        }

        $attacker = $e->getPlayerByNickname($e->clearNickname($e->getAction()->childNodes->item(0)->textContent));
        $attacker->setMisses($attacker->getMisses() + 1);

        $e->stopPropagation();
    }
}