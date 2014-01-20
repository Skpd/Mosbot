<?php

namespace Web\Fight\Events;

use Web\Fight\Event;
use Web\Fight\Exception\PlayerNotFoundException;
use Zend\Dom\Query;

class Miss
{
    public function __invoke(Event $evt)
    {
        $query = new Query(
            '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>'
            . $evt->getAction()->ownerDocument->saveHTML($evt->getAction())
            . '</body></html>'
        );

        if ($query->execute('.miss')->count() !== 1) {
            return null;
        }

        try {
            $attacker = $evt->getPlayerByNickname($evt->clearNickname(
                $query->execute('.name-resident, .name-arrived, .name-neutral')->current()->textContent
            ));
            $attacker->setMisses($attacker->getMisses() + 1);
        } catch (PlayerNotFoundException $e) {
            //pet miss, do nothing
        }

        $evt->stopPropagation();
        return __CLASS__;
    }
}