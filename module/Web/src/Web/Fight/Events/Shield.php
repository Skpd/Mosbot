<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Shield
{
    function __invoke(Event $evt)
    {
        if ($evt->getAction()->attributes
            && (
                $evt->getAction()->attributes->getNamedItem('class')->textContent == 'antigranata2 '
                || $evt->getAction()->attributes->getNamedItem('class')->textContent == 'antigranata '
            )
        ) {
            $evt->stopPropagation();
            return __CLASS__;
        }
    }
}