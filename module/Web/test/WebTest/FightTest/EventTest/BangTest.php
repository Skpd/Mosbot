<?php

namespace WebTest\FightTest\EventTest;

use Web\Fight\Events\Bang;
use WebTest\FightTest\EventTest;

class BangTest extends EventTest
{
    public function testBangThrowHit()
    {
        $html = <<<HTML
<p class="bang-throw "><span class="name-arrived"><b>SaV&nbsp;[11]</b></span> метает в толпу дерущихся <b>Граната «Морская»</b></p>
HTML;
        $feature = new Bang();
        $event   = $this->getEvent();
        $event->setAction($this->createNode($html));

        $this->assertNotNull($feature($event), __FUNCTION__);
    }

    public function testBangHit()
    {
        $throwHtml = <<<HTML
<p class="bang-throw "><span class="name-arrived"><b>SaV&nbsp;[11]</b></span> метает в толпу дерущихся <b>Граната «Морская»</b></p>
HTML;
        $hitHtml = <<<HTML
<p class="bang "><span class="icon icon-bang"></span><span class="name-resident"><b>Yarfond&nbsp;[11]</b></span> получает урон от взрыва  (–188)</p>
HTML;

        $feature = new Bang();
        $event   = $this->getEvent();

        $event->setAction($this->createNode($hitHtml));
        $this->assertNull($feature($event), 'hit without attacker');

        $event->setAction($this->createNode($throwHtml));
        $feature($event);
        $event->setAction($this->createNode($hitHtml));
        $this->assertNotNull($feature($event), 'hit with attacker');
    }

    public function testBangPoisonHit()
    {
        $throwHtml = <<<HTML
<p class="bang-throw "><span class="name-resident"><b>gun_ur&nbsp;[11]</b></span> метает в толпу дерущихся <b>Бомба-вонючка</b></p>
HTML;
        $hitHtml = <<<HTML
<p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>gun_ur&nbsp;[11]</b></span> получает урон от взрыва  (–315)</p>
HTML;
        $poisonHtml = <<<HTML
<p class="bang-poison "><span class="icon icon-bang-poison"></span><span class="name-arrived"><b>gun_ur&nbsp;[11]</b></span> получает отравление дымом  (–150)</p>
HTML;

        $feature = new Bang();
        $event   = $this->getEvent();

        $event->setAction($this->createNode($poisonHtml));
        $this->assertNull($feature($event), __FUNCTION__);

        $event->setAction($this->createNode($throwHtml));
        $this->assertNotNull($feature($event), 'throw');

        $event->setAction($this->createNode($hitHtml));
        $this->assertNotNull($feature($event), 'hit');

        $event->setAction($this->createNode($poisonHtml));
        $this->assertNotNull($feature($event), 'poison 1');
        $this->assertNotNull($feature($event), 'poison 2');
        $this->assertNotNull($feature($event), 'poison 3');
        $this->assertNull($feature($event), 'poison end');
    }
}