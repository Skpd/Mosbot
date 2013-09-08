<?php

namespace Web\Fight\Events;

use Web\Fight\Event;
use Web\Fight\Exception\PlayerNotFoundException;

class PetHit
{
    public function __invoke(Event $evt)
    {
        if (!in_array($evt->getAction()->firstChild->attributes->getNamedItem('class')->textContent, ['name-resident', 'name-arrived'])
            && !in_array($evt->getAction()->childNodes->item(1)->attributes->getNamedItem('class')->textContent, ['name-resident', 'name-arrived'])) {
            return;
        }

        try {
            //checking victim
            $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item($evt->getAction()->childNodes->length - 2)->textContent));

            $prevNode = $evt->getAction()->previousSibling;

            if ($prevNode->childNodes->length == 4 || $prevNode->childNodes->length == 3) {
                $attacker = $evt->getPlayerByNickname($evt->clearNickname(
                    $prevNode->childNodes->item(0)->textContent
                ));
            } elseif ($prevNode->childNodes->length == 5) {
                $attacker = $evt->getPlayerByNickname($evt->clearNickname(
                    $prevNode->childNodes->item(1)->textContent
                ));
            } else {
                \Zend\Debug\Debug::dump($evt->getAction());
                exit;
            }

            $damage = $evt->clearDamage($evt->getAction()->lastChild->textContent);
            $attacker->incrementDamage($damage);
            $evt->stopPropagation();
        } catch (PlayerNotFoundException $e) {
            // pet-pet hit
            $evt->stopPropagation();
            return;
        }
    }
}