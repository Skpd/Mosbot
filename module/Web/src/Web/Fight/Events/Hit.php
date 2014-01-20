<?php

namespace Web\Fight\Events;

use Web\Fight\Event;
use Web\Fight\Exception\PlayerNotFoundException;
use Zend\Dom\Query;

class Hit
{
    public function __invoke(Event $evt)
    {
        $query = new Query(
            '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>'
            . $evt->getAction()->ownerDocument->saveHTML($evt->getAction())
            . '</body></html>'
        );

        if ($query->execute('.name-resident, .name-arrived, .name-neutral')->count() === 2
            && ($query->execute('.punch')->count() === 1 || $query->execute('.reflected')->count() === 1)
        ) {
            if (!preg_match('/\(-(\d+)\)/', $evt->getAction()->textContent, $matches)) {
                return null;
            }

            $players = $query->execute('.name-resident, .name-arrived, .name-neutral');
            $damage  = $matches[1];

            try {
                if ($query->execute('.reflected')->count()) {
                    $victim   = $evt->getPlayerByNickname($evt->clearNickname($players->current()->textContent));
                    $attacker = $evt->getPlayerByNickname($evt->clearNickname($players->next()->textContent));
                } else {
                    $attacker = $evt->getPlayerByNickname($evt->clearNickname($players->current()->textContent));
                    $victim   = $evt->getPlayerByNickname($evt->clearNickname($players->next()->textContent));
                }
            } catch (PlayerNotFoundException $e) {
                //pet hit. todo: should we count pet damage?
                $evt->stopPropagation();
                return __CLASS__;
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