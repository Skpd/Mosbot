<?php

namespace Web\Service;

use Web\Document\FightResult;
use Web\Fight\Event;
use Zend\Dom\Query;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\ClientStatic as HttpClient;
use DOMNode;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FightAnalyzer implements EventManagerAwareInterface, ServiceLocatorAwareInterface
{
    /** @var EventManagerInterface */
    private $events;
    private $documentManager;
    private $serviceLocator;
    private $baseUrl = 'http://www.roswar.ru/fight/';

    public function analyze($id)
    {
        $result = $this->getDocumentManager()->find('Web\Document\FightResult', $id);

        if (!empty($result)) {
            $this->getDocumentManager()->remove($result);
            $this->getDocumentManager()->flush();
        }

        $result = new FightResult();
        $result->setId($id);

        $query  = new Query(HttpClient::get($this->baseUrl . $id . '/0/')->getBody());

        $links = [];

        foreach ($query->execute('.pagescroll .block-rounded > *') as $pageLink) {
            /** @var DomNode $pageLink */
            $pageNumber = $pageLink->textContent;

            if (is_numeric($pageNumber)) {
                $links[] = $pageNumber;
            }
        }

        // add first page
        $links[] = 0;
        $links   = array_reverse($links);

        foreach ($links as $pageNumber) {
            $this->processPage($pageNumber, $id, $result);
        }

        $this->getDocumentManager()->persist($result);
        $this->getDocumentManager()->flush($result);

        return $result;
    }

    public function processPage($pageNumber, $id, FightResult $result)
    {
        $events = $this->getEventManager();
        $event  = new Event();
        $query  = new Query(HttpClient::get($this->baseUrl . $id . '/' . $pageNumber . '/')->getBody());

        $event->setResult($result);
        $event->setTarget($this);
        $event->setQuery($query);

        $events->trigger('analyze.pre', $event);

        foreach ($query->execute('.fight-log .text p') as $action) {
            /** @var DomNode $action */

            $event->setAction($action);

            $results = $events->trigger('analyze.action', $event);

            if (!$results->stopped()) {
                \Zend\Debug\Debug::dump($action, 'Cant handle');exit;
            }
        }

        $events->trigger('analyze.post', $this, $event);
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(
            array(
                __CLASS__,
                get_called_class(),
            )
        );
        $this->events = $eventManager;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
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

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}