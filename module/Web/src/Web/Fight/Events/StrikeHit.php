<?php

namespace Web\Fight\Events;

use Web\Fight\Event;
use Web\Fight\Exception\PlayerNotFoundException;

class StrikeHit
{
    public function __invoke(Event $evt)
    {
        if ($evt->getAction()->childNodes->item(0)->attributes->getNamedItem('class')->textContent != 'icon serial') {
            return;
        }

        if ($evt->getAction()->childNodes->item(2)->attributes->getNamedItem('class')->textContent != 'punch') {
            return;
        }

        try {
            $attacker = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(1)->textContent));
        } catch (PlayerNotFoundException $e) {
            return;
        }

        $damage = $evt->clearDamage($evt->getAction()->childNodes->item(4)->textContent);
        $attacker->incrementDamage($damage);
        $attacker->setNormalHits($attacker->getNormalHits() + 1);
        $evt->stopPropagation();
        return __CLASS__;
    }
}