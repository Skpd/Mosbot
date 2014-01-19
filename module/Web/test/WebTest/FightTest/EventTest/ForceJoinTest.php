<?php

namespace WebTest\FightTest\EventTest;

use Web\Fight\Events\ForceJoin;
use WebTest\FightTest\EventTest;

class ForceJoinTest extends EventTest
{
    public function testForceJoin()
    {
        $feature = new ForceJoin();
        $event   = $this->getEvent();
        $event->setAction($this->createNode('force-join.html'));

        $this->assertNotNull($feature($event), __FUNCTION__);
    }
}