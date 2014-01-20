<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Shout
{
    function __invoke(Event $evt)
    {
        if (stristr($evt->getAction()->textContent, 'кричит:')) {
            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}