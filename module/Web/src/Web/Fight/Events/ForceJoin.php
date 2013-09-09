<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class ForceJoin
{
    function __invoke(Event $evt)
    {
        if ($evt->getAction()->attributes && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'forcejoin ') {
            $evt->stopPropagation();
        }
    }
}