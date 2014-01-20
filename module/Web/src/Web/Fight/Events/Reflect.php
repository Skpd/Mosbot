<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Reflect
{
    function __invoke(Event $evt)
    {
        //reflect ready
        if ($evt->getAction()->attributes && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'reflect ') {
            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}