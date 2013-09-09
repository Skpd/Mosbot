<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class HelmetHit
{
    function __invoke(Event $evt)
    {
        if (
            //helmet use
            (
                $evt->getAction()->attributes
                && $evt->getAction()->attributes->getNamedItem('class')->textContent == 'helmet '
            )
            ||
            //helmet hit
            (
                $evt->getAction()->lastChild
                && $evt->getAction()->lastChild->attributes
                && $evt->getAction()->lastChild->attributes->getNamedItem('class')
                && $evt->getAction()->lastChild->attributes->getNamedItem('class')->textContent == 'helmethit'
            )
        ) {
            $evt->stopPropagation();
        }
    }
}