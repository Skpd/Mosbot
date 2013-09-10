<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Bang
{
    /** @var \Web\Document\PlayerStat */
    private $attacker;

    private $poisonGrenades = ['Грязный носок' => 3];
    private $poisoned = false;
    private $poisonedTargets = [];

    function __invoke(Event $evt)
    {
        if ($evt->getAction()->attributes->getNamedItem('class')->textContent == 'bang-throw ') {
            $this->attacker = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(0)->textContent));
            $this->attacker->incrementThrows();

            $grenade        = $evt->getAction()->lastChild->textContent;
            $this->poisoned = isset($this->poisonGrenades[$grenade]) ? $this->poisonGrenades[$grenade] : false;

            $evt->stopPropagation();
            return __CLASS__;
        }

        if ($this->attacker && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'bang ') {
            $victim = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(1)->textContent));

            if ($this->attacker->getTeam() != $victim->getTeam()) {
                $damage = $evt->clearDamage($evt->getAction()->lastChild->textContent);
                $this->attacker->incrementDamage($damage);
                $this->attacker->incrementGrenadeDamage($damage);
            }

            if ($this->poisoned) {
                $this->poisonedTargets[] = ['attacker' => $this->attacker, 'victim' => $victim, 'rounds' => $this->poisoned];
            }

            $evt->stopPropagation();
            return __CLASS__;
        }

        if ($evt->getAction()->attributes && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'bang-poison ') {
            $victim = $evt->getPlayerByNickname($evt->clearNickname($evt->getAction()->childNodes->item(1)->textContent));
            $damage = $evt->clearDamage($evt->getAction()->lastChild->textContent);

            foreach ($this->poisonedTargets as $k => $target) {
                if ($target['victim']->getPlayer()->getId() == $victim->getPlayer()->getId()) {
                    $this->poisonedTargets[$k]['rounds']--;
                    $target['attacker']->incrementDamage($damage);
                    $target['attacker']->incrementGrenadeDamage($damage);
                }

                if ($this->poisonedTargets[$k]['rounds'] == 0) {
                    unset($this->poisonedTargets[$k]);
                }
            }

            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}