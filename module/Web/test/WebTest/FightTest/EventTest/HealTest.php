<?php

namespace WebTest\FightTest\EventTest;

use Web\Fight\Events\Heal;
use WebTest\FightTest\EventTest;

class HealTest extends EventTest
{
    public function testHeal()
    {
        $feature = new Heal();
        $event   = $this->getEvent();
        $event->setAction($this->createNode('heal.html'));

        $this->assertNotNull($feature($event), __FUNCTION__);
    }
}