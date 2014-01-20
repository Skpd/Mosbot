<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Banish
{
    function __invoke(Event $evt)
    {
        if (stristr($evt->getAction()->textContent, 'прогоняет из боя')) {
            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}