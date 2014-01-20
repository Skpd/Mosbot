<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Flag
{
    function __invoke(Event $evt)
    {
        if (stristr($evt->getAction()->textContent, 'сохраняет флаг') || stristr($evt->getAction()->textContent, 'перехватывает флаг')) {
            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}