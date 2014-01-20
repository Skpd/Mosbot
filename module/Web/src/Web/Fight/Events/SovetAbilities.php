<?php

namespace Web\Fight\Events;

use Web\Fight\Event;
use Zend\Dom\Query;

class SovetAbilities
{
    function __invoke(Event $evt)
    {
        //<p class=""><span class="name-resident"><b>Yarfond [11]</b></span> <span class="sovetabil6"><i class="icon"></i>вычитает НДС</span> у <span class="name-arrived"><b>Итиан [11]</b></span> ( <b>-2810</b>).</p>
        /* <p class="">
            <span class="name-arrived"><b>мистер 007 [11]</b></span>
            <span class="sovetabil4"><i class="icon"></i>устраивает допрос</span>
            <span class="name-resident"><b>DJJonh [11]</b></span>
            (-<b>1030</b>) и восстанавливает себе
            <i class="icon-vampire"></i><span tooltip="1">излечивает <b>1122</b> жизней</span>
        </p> */

        /*
            <p class="">
                <span class="name-arrived"><b>lio666 [11]</b></span>
                переходит на
                <span class="sovetabil3 tip" tooltip="1"><i class="icon"></i>зимнее время</span>
                на 2 ходов.
            </p>
         */

        /*
        <p class=""><span class="name-resident"><b>001qwer100 [11]</b></span> окружает себя <span class="spike-injury"><i class="icon icon-spike"></i>неровной плиткой</span>, при ударе по нему противники получают 20% нанесенного урона. Плитка лежит еще <b>4</b> ходов</p>
         */

        /*
        <p class="">
            <span class="name-resident"><b>DJJonh [11]</b></span>
            <span class="sovetabil8"><i class="icon"></i>проверяет регистрацию</span>
            у игроков в бою. Были ранены: <b>Nyaaa [11]</b> (-82), <b>Даримка [11]</b> (-138), <b>Шелест [11]</b> (-106)</p>
         */
        // <p class=""><span class="name-arrived"><b>Хамза [11]</b></span> <span class="sovetabil7"><i class="icon"></i>командует ОМОНу</span> разгонять несогласных.</p>
        $query = new Query(
            '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>'
            . $evt->getAction()->ownerDocument->saveHTML($evt->getAction())
            . '</body></html>'
        );

        if ($query->execute('.sovetabil8')->count()) {
            $attacker = $evt->getPlayerByNickname($evt->clearNickname($query->execute('.name-resident, .name-arrived, .name-neutral')->current()->textContent));

            if (preg_match_all('/\(-(\d+)\)/', $evt->getAction()->textContent, $matches)) {
                foreach ($matches[1] as $damage) {
                    $attacker->incrementDamage($evt->clearDamage($damage));
                }
            }

            $evt->stopPropagation();
            return __CLASS__;
        }

        if ($query->execute('.spike-injury')->count()) {
            $evt->stopPropagation();
            return __CLASS__;
        }

        if ($query->execute('.sovetabil3')->count()) {
            $evt->stopPropagation();
            return __CLASS__;
        }

        if ($query->execute('.sovetabil7')->count()) {
            $evt->stopPropagation();
            return __CLASS__;
        }

        if ($query->execute('.sovetabil4')->count()) {
            $attacker = $evt->getPlayerByNickname($evt->clearNickname($query->execute('.name-resident, .name-arrived, .name-neutral')->current()->textContent));
            $damage   = $evt->clearDamage($query->execute('p > b')->current()->textContent);
            $healed   = $evt->clearDamage($evt->getAction()->lastChild->textContent);

            $attacker->incrementDamage($damage);
            $attacker->incrementHealed($healed);

            $evt->stopPropagation();
            return __CLASS__;
        }

        if ($query->execute('.sovetabil6')->count()) {
            $attacker = $evt->getPlayerByNickname($evt->clearNickname($query->execute('.name-resident, .name-arrived, .name-neutral')->current()->textContent));
            $damage   = $evt->clearDamage($query->execute('p > b')->current()->textContent);

            $attacker->incrementDamage($damage);

            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}