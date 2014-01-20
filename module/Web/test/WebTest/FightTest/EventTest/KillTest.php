<?php

namespace WebTest\FightTest\EventTest;

use Web\Fight\Events\Kill;
use WebTest\FightTest\EventTest;

class KillTest extends EventTest
{
    public function testKill()
    {
        $feature = new Kill();
        $event   = $this->getEvent();

        $event->setAction($this->createNode('kill.html'));
        $this->assertNotNull($feature($event), __FUNCTION__);
    }
}