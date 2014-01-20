<?php

namespace WebTest\FightTest\EventTest;

use Web\Fight\Events\HelmetHit;
use WebTest\FightTest\EventTest;

class HelmetTest extends EventTest
{
    public function testHelmet()
    {
        $feature = new HelmetHit();
        $event   = $this->getEvent();

        $event->setAction($this->createNode('helmet-hit.html'));
        $this->assertNotNull($feature($event), __FUNCTION__);

        $event->setAction($this->createNode('helmet-use.html'));
        $this->assertNotNull($feature($event), __FUNCTION__);
    }
}