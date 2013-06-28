<?php

namespace Runner\Controller;

use Runner\Document\Player;
use Zend\Mvc\Controller\AbstractActionController;
use Runner\Service\Spider;

class SpiderController extends AbstractActionController
{
    /** @var Spider */
    private $spider;
    private $documentManager;

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

            foreach ($ids as $id) {
                /** @var $player Player */
                $player = $this->getDocumentManager()->find('Runner\Document\Player', $id);

                if (empty($player)) {
                    $player = new Player;
                }

                $player->setId(intval($id));

                $this->getSpider()->updatePlayer($player);

                $this->getDocumentManager()->persist($player);

                if ($this->getDocumentManager()->getUnitOfWork()->size() >= 10) {
                    $this->getDocumentManager()->flush();
                }
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