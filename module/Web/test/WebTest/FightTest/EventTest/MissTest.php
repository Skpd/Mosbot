<?php

namespace WebTest\FightTest\EventTest;

use Web\Fight\Events\Miss;
use WebTest\FightTest\EventTest;

class MissTest extends EventTest
{
    public function testMiss()
    {
        $feature = new Miss();
        $event   = $this->getEvent();

        $event->setAction($this->createNode('miss.html'));
        $this->assertNotNull($feature($event), __FUNCTION__);
    }
}