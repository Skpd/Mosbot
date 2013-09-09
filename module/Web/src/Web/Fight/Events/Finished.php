<?php

namespace Web\Fight\Events;

use Web\Fight\Event;

class Finished
{
    public function __invoke(Event $evt)
    {
        $links = $evt->getQuery()->execute('.pagescroll .block-rounded > *');

        if ($links->current()->attributes->getNamedItem('class')->textContent == 'current') {
            if ($evt->getQuery()->execute('.fight-log .result')->count()) {
                $evt->getResult()->setFinished(true);
            }
        }
    }
}