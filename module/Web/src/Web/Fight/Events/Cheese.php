<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Cheese
{
    function __invoke(Event $evt)
    {
        if (
            $evt->getAction()->attributes
            && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'cheese '
        ) {
            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}