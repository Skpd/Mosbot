<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Flag
{
    function __invoke(Event $evt)
    {
        if (stristr($evt->getAction()->textContent, 'сохраняет флаг')) {
            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}