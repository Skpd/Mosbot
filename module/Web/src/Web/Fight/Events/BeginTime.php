<?php

namespace Web\Fight\Events;

use DateTime;
use Web\Fight\Event;

class BeginTime
{
    function __invoke(Event $e)
    {
        if (!$e->getResult()->getDate() && preg_match('/(\d{2}:\d{2}:\d{2}\s\(\d{2}\.\d{2}\.\d{4}\))/', $e->getAction()->textContent, $m)) {
            $date = DateTime::createFromFormat('H:i:s (d.m.Y)', $m[1], new \DateTimeZone('Europe/Moscow'));
            $e->getResult()->setDate($date);

            $e->stopPropagation();
            return __CLASS__;
        }

        return false;
    }
}