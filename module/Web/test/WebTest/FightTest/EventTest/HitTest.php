<?php

namespace WebTest\FightTest\EventTest;

use Web\Fight\Events\Hit;
use WebTest\FightTest\EventTest;

class HitTest extends EventTest
{
    public function testHit()
    {
        $feature = new Hit();
        $event   = $this->getEvent();

        $event->setAction($this->createNode('hit.html'));
        $this->assertNotNull($feature($event), __FUNCTION__);
    }

    public function testHitStrike()
    {
        $feature = new Hit();
        $event   = $this->getEvent();

        $event->setAction($this->createNode('hit-strike.html'));
        $this->assertNotNull($feature($event), __FUNCTION__);
    }

    public function testHitSpike()
    {
        $feature = new Hit();
        $event   = $this->getEvent();

        $event->setAction($this->createNode('hit-spike.html'));
        $this->assertNotNull($feature($event), __FUNCTION__);
    }

    public function testHitCritical()
    {
        $feature = new Hit();
        $event   = $this->getEvent();

        $event->setAction($this->createNode('hit-critical.html'));
        $this->assertNotNull($feature($event), __FUNCTION__);
    }

    public function testHitSelfMarker()
    {
        $feature = new Hit();
        $event   = $this->getEvent();

        $event->setAction($this->createNode('hit-self-marker.html'));
        $this->assertNotNull($feature($event), __FUNCTION__);
    }
}