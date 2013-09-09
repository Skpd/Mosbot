<?php

namespace Web\Fight\Events;

use Web\Fight\Event;
use Web\Fight\Exception\PlayerNotFoundException;

class Reflect
{
    function __invoke(Event $evt)
    {
        //reflect ready
        if ($evt->getAction()->attributes && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'reflect ') {
            $evt->stopPropagation();
        }

        //normal hit
        if ($evt->getAction()->childNodes->length == 4 && $evt->getAction()->childNodes->item(1)->attributes->getNamedItem('class')->textContent == 'reflected') {
            $attacker = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(0)->textContent));
            $damage = $evt->clearDamage($evt->getAction()->lastChild->textContent);

            try {
                $victim = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(2)->textContent));
                $attacker->incrementDamage($damage);
            } catch (PlayerNotFoundException $e) {
                //reflected pet hit, do nothing
            }

            $evt->stopPropagation();
            return;
        }

        //strike hit
        if ($evt->getAction()->childNodes->length == 5 && $evt->getAction()->childNodes->item(2)->attributes && $evt->getAction()->childNodes->item(2)->attributes->getNamedItem('class')->textContent == 'reflected') {
            $attacker = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(1)->textContent));
            $damage = $evt->clearDamage($evt->getAction()->lastChild->textContent);

            try {
                $victim = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(3)->textContent));
                $attacker->incrementDamage($damage);
            } catch (PlayerNotFoundException $e) {
                //reflected pet hit, do nothing
            }

            $evt->stopPropagation();
            return;
        }
    }
}