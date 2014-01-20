<?php

namespace Web\Fight\Events;

use Web\Fight\Event;
use Web\Fight\Exception\PlayerNotFoundException;
use Zend\Dom\Query;

class Hit
{
    public function __invoke(Event $evt)
    {
        $query = new Query($evt->getAction()->ownerDocument->saveHTML());

        if ($query->execute('.name-resident, .name-arrived, .name-neutral')->count() === 2
            && $query->execute('.punch')->count() === 1
        ) {
            if (!preg_match('/\(-(\d+)\)/', $evt->getAction()->textContent, $matches)) {
                return null;
            }

            $players = $query->execute('.name-resident, .name-arrived, .name-neutral');
            $damage  = $matches[1];

            try {
                $attacker = $evt->getPlayerByNickname($evt->clearNickname($players->current()->textContent));
                $victim   = $evt->getPlayerByNickname($evt->clearNickname($players->next()->textContent));
            } catch (PlayerNotFoundException $e) {
                return null;
            }

            $attacker->incrementDamage($damage);

            if ($query->execute('.critical')->count()) {
                $attacker->setCriticalHits($attacker->getCriticalHits() + 1);
            } else {
                $attacker->setNormalHits($attacker->getNormalHits() + 1);
            }

            if ($query->execute('.spike-injury')->count()) {
                $victim->incrementDamage($evt->clearDamage($query->execute('.spike-injury')->current()->textContent));
            }

            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}