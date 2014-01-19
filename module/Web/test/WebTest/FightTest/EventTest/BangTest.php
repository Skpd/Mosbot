<?php

namespace WebTest\FightTest\EventTest;

use Web\Fight\Events\Bang;
use WebTest\FightTest\EventTest;

class BangTest extends EventTest
{
    public function testBangThrowHit()
    {
        $feature = new Bang();
        $event   = $this->getEvent();
        $event->setAction($this->createNode('bang-throw.html'));

        $this->assertNotNull($feature($event), __FUNCTION__);
    }

    public function testBangHit()
    {
        $feature = new Bang();
        $event   = $this->getEvent();

        $event->setAction($this->createNode('bang-hit.html'));
        $this->assertNull($feature($event), 'hit without attacker');

        $event->setAction($this->createNode('bang-throw.html'));
        $feature($event);
        $event->setAction($this->createNode('bang-hit.html'));
        $this->assertNotNull($feature($event), 'hit with attacker');
    }

    public function testBangPoisonHit()
    {
        $feature = new Bang();
        $event   = $this->getEvent();

        $event->setAction($this->createNode('bang-poison.html'));
        $this->assertNull($feature($event), __FUNCTION__);

        $event->setAction($this->createNode('bang-throw.html'));
        $this->assertNotNull($feature($event), 'throw');

        $event->setAction($this->createNode('bang-hit.html'));
        $this->assertNotNull($feature($event), 'hit');

        $event->setAction($this->createNode('bang-poison.html'));
        $this->assertNotNull($feature($event), 'poison 1');
        $this->assertNotNull($feature($event), 'poison 2');
        $this->assertNotNull($feature($event), 'poison 3');
        $this->assertNull($feature($event), 'poison end');
    }
}