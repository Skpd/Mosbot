<?php

namespace WebTest\FightTest\EventTest;

use Web\Document\FightResult;
use Web\Fight\Event;
use WebTest\Bootstrap;
use WebTest\FightTest\EventTest;
use Zend\Dom\Query;
use Zend\EventManager\EventManager;
use Web\Fight\Events;


class AllInOneTest extends EventTest
{
    public function testFight()
    {
        $eventManager = new EventManager();

        $eventManager->attach('analyze.action', new Events\BeginTime());
        $eventManager->attach('analyze.action', new Events\Separator());
        $eventManager->attach('analyze.action', new Events\Kill());
        $eventManager->attach('analyze.action', new Events\Heal());
        $eventManager->attach('analyze.action', new Events\ForceJoin());
        $eventManager->attach('analyze.action', new Events\Bang());
        $eventManager->attach('analyze.action', new Events\HelmetHit());
        $eventManager->attach('analyze.action', new Events\Shield());
        $eventManager->attach('analyze.action', new Events\Hit());
        $eventManager->attach('analyze.action', new Events\Miss());
        $eventManager->attach('analyze.action', new Events\Reflect());
        $eventManager->attach('analyze.action', new Events\Cheese());
        $eventManager->attach('analyze.action', new Events\SovetAbilities());
        $eventManager->attach('analyze.action', new Events\Banish());
        $eventManager->attach('analyze.action', new Events\Flag());

        $eventManager->attach('analyze.pre', new Events\Teams());

        $eventManager->attach('analyze.post', new Events\Winner());
        $eventManager->attach('analyze.post', new Events\DamagePercentage());
        $eventManager->attach('analyze.post', new Events\Finished());

        $result = new FightResult();

        $pages = [];

        foreach (new \DirectoryIterator(Bootstrap::getFixturePath() . 'testFight') as $page) {
            /** @var \DirectoryIterator $page */

            if (!$page->isDot() && !$page->isDir()) {
                $pages[substr($page->getFilename(), 0, -5)] = $page->getRealPath();
            }
        }

        ksort($pages);

        foreach ($pages as $page) {
            $event  = new Event();
            $query  = new Query(
                '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>'
                . file_get_contents($page)
                . '</body></html>'
            );

            $event->setResult($result);
            $event->setQuery($query);

            $eventManager->trigger('analyze.pre', $event);

            foreach ($query->execute('.fight-log .text p') as $action) {
                /** @var \DomNode $action */

                $event->setAction($action);

                $results = $eventManager->trigger('analyze.action', $event);

                if (!$results->stopped()) {
                    $text = $action->textContent;
                    $html = $action->ownerDocument->saveHTML($action);
                    throw new \Exception('Cant handle ' . $text);
                }
            }

            $eventManager->trigger('analyze.post', $this, $event);
        }

        $mostDamage = $result->getPlayers()->filter(function ($player) {
            return $player->getPlayer()->getNickname() === 'Nyaaa';
        })->current();

        $this->assertEquals(71936, $mostDamage->getDamage());
    }
}