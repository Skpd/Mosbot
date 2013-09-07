<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Separator
{
    public function __invoke(Event $e)
    {
        if ($e->getAction()->attributes->getNamedItem('class')->textContent == 'line') {
            $e->stopPropagation();
            return;
        }

        if ($e->getAction()->childNodes->length == 0) {
            $e->stopPropagation();
            return;
        }
    }
}