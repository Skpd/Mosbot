<?php

namespace Web\Fight\Events;

use Web\Fight\Event;
use Web\Fight\Exception\PlayerNotFoundException;

class PetHit
{
    public function __invoke(Event $evt)
    {
        if ($evt->getAction()->childNodes->length >= 4
            && $evt->getAction()->childNodes->item(1)->attributes->getNamedItem('class')->textContent != 'punch'
            && $evt->getAction()->childNodes->item(2)->attributes
            && $evt->getAction()->childNodes->item(2)->attributes->getNamedItem('class')->textContent != 'punch'
        ) {
            return;
        }

        try {
            //checking victim
            $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item($evt->getAction()->childNodes->length - 2)->textContent));

//            if ($evt->getAction()->childNodes->length == 5 && $evt->getAction()->firstChild->attributes->getNamedItem('class')->textContent != 'icon serial') {
//                // pet-player focused hit
//                $evt->stopPropagation();
//                return __CLASS__;
//            }

            $prevNode = $evt->getAction()->previousSibling;

            if ($prevNode->childNodes->length == 4 || $prevNode->childNodes->length == 3) {
                $attacker = $evt->getPlayerByNickname($evt->clearNickname(
                    $prevNode->childNodes->item(0)->textContent
                ));
            } elseif ($prevNode->childNodes->length == 5) {
                $attacker = $evt->getPlayerByNickname($evt->clearNickname(
                    $prevNode->childNodes->item(1)->textContent
                ));
            }

            if (isset($attacker)) {
                $damage = $evt->clearDamage($evt->getAction()->lastChild->textContent);
                $attacker->incrementDamage($damage);
            }

            $evt->stopPropagation();
            return __CLASS__;
        } catch (PlayerNotFoundException $e) {
            // pet-pet hit
            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}