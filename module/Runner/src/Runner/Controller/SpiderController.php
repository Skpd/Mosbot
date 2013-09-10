<?php

namespace Runner\Controller;

use jyggen\Curl;
use Runner\Document\Player;
use Zend\Dom\Query;
use Zend\Mvc\Controller\AbstractActionController;
use Runner\Service\Spider;

class SpiderController extends AbstractActionController
{
    /** @var Spider */
    private $spider;
    private $documentManager;

    public function updateByLevelAction()
    {
        $level = intval($this->params('level', 0));

        $players = $this->getDocumentManager()->getRepository('Runner\Document\Player')->findBy(['level' => $level]);

        echo $players->count() . PHP_EOL;
        $players->rewind();
        $z = 0;
        while ($players->valid()) {
            $cache = [];

            for ($i = 0; $i < 1 && $players->valid(); $i++) {
                $player = $players->current();

//                echo $player->getId();

                $cache['links'][$i] = 'http://www.roswar.ru/player/' . $player->getId() . '/';
                $cache['players'][$i] = $player;

                $players->next();
            }
            $t = microtime(1);
            $responses = Curl::get($cache['links']);
            echo microtime(1) - $t . PHP_EOL;
            foreach ($responses as $k => $response) {
                $t = microtime(1);
                $query = new Query($response->getContent());


                $this->getSpider()->parsePlayer($cache['players'][$k], $query);
                $this->getDocumentManager()->persist($cache['players'][$k]);

                if ($this->getDocumentManager()->getUnitOfWork()->size() >= 10) {
                    $this->getDocumentManager()->flush();
                    $this->getDocumentManager()->clear();
                }

                echo (microtime(1) - $t) . ' ' . $player->getId() .  ' ' . (++$z) . '/' . $players->count() . PHP_EOL;
            }
        }

        $this->getDocumentManager()->flush();
        $this->getDocumentManager()->clear();
    }

    public function getPlayersAction()
    {
        $ids = $this->params('id', null);

        if (!empty($ids)) {
            if (strstr($ids, '-')) {
                $ids = explode('-', $ids, 2);
                sort($ids);
                $ids = range($ids[0], $ids[1], 1);
            } else {
                $ids = array($ids);
            }


//            $childCount = 10;
//
//            /** @var \ParallelJobs\System\Fork\ForkManager $manager */
//            $manager = $this->serviceLocator->get('ForkManager');
//            $manager->setAutoStart(false);
//            $manager->createChildren($childCount);
//
//            for ($i = 0; $i < count($ids); $i += $childCount) {
//                $manager->createChildren($childCount);
//                for ($child = 0; $child < $childCount; $child++) {
//                    if ($i + $child >= count($ids)) break;
//                    /** @var $player Player */
//                    $player = $this->getDocumentManager()->find('Runner\Document\Player', $ids[$i + $child]);
//
//                    if (empty($player)) {
//                        $player = new Player;
//                        $player->setId(intval($ids[$i + $child]));
//                    }
//
//                    $manager->doTheJobChild($child, array($this->getSpider(), 'updatePlayer'), $player);
//                }
//
//                $manager->start();
//                $manager->wait();
//                $manager->rewind();
//            }

            foreach ($ids as $id) {
                $player = $this->getDocumentManager()->find('Runner\Document\Player', intval($id));

                if (empty($player)) {
                    $player = new Player;
                    $player->setId(intval($id));
                }

                $this->getSpider()->updatePlayer($player);
                $player = null;
            }


        } else {
            //todo: update existing players
        }

        $this->getDocumentManager()->flush();
    }

    /**
     * @return Spider
     */
    public function getSpider()
    {
        if (null === $this->spider) {
            $this->spider = $this->serviceLocator->get('spider');
        }

        return $this->spider;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager()
    {
        if (null === $this->documentManager) {
            $this->documentManager = $this->serviceLocator->get('doctrine.documentmanager.odm_default');
        }

        return $this->documentManager;
    }
}