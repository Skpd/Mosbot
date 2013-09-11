<?php

namespace Runner\Service;

use Runner\Document\Player;
use Zend\Dom\Query;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Http\Client;

class Spider implements ServiceLocatorAwareInterface
{
    /** @var ServiceLocatorInterface */
    private $serviceLocator;

    /** @var Client */
    private $client;
    private $baseUrl = 'http://www.roswar.ru/player/';

    public function updatePlayer(Player $player)
    {
        $t = microtime(1);

        $query = $this->getContents($player);
        $this->parsePlayer($player, $query);

        $dm = $this->serviceLocator->get('doctrine.documentmanager.odm_default');
        $dm->persist($player);

        if ($dm->getUnitOfWork()->size() >= 100) {
            $dm->flush();
            $dm->clear();
        }

        echo 'Done ' . $player->getId() . ' (' . (microtime(1) - $t) . ' sec ' . memory_get_peak_usage(1) . ' mem)' .  PHP_EOL;
    }

    public function getContents(Player $player)
    {
        $contents = $this->getClient()->setUri($this->baseUrl . $player->getId())->send()->getBody();
        return new Query($contents);
    }

    public function parsePlayer(Player $player, Query $query)
    {
        $alignment = $query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' user ')]/descendant::i")->current();

        if (!empty($alignment)) {
            $player->setAlignment($alignment->attributes->getNamedItem('class')->textContent);
        } else {
            echo 'Nothing to do here ' . $player->getId() . PHP_EOL;
            return;
        }

        $player->setNickname($query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' user ')]/descendant::a[@href = '/player/{$player->getId()}/']")->current()->textContent);
        $player->setLevel(str_replace(['[', ']'], '', $query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' user ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' level ')]")->current()->textContent));

        if ($query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' user ')]/descendant::a")->count() === 2) {
            $player->setClan(preg_replace('/[^\d]+/', '', $query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' user ')]/descendant::a")->current()->attributes->getNamedItem('href')->textContent));
        } else {
            $player->setClan(0);
        }

        if ($query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' blocked ')]")->count()) {
            $player->setState(Player::STATE_BLOCKED);
        }

        if ($query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' frozen ')]")->count()) {
            $player->setState(Player::STATE_FROZEN);
        }

        if ($query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' jail-icon ')]")->count()) {
            $player->setState(Player::STATE_JAIL);
        }

        foreach ($query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' stats ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' stat ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' num ')]") as $k => $stat) {
            if ($k >= 7) {
                break;
            }

            /** @var $stat \DOMNode */
            $setter = 'set' . ucfirst($stat->parentNode->parentNode->attributes->getNamedItem('data-type')->textContent);

            if (method_exists($player, $setter)) {
                $player->{$setter}($stat->textContent);
            }
        }

        $statistics = $query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' pers-statistics ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' numbers ')]/descendant::li");

        $player->setWins((int) preg_replace('/[^\d]/', '', $statistics->offsetGet(1)->textContent));
        $player->setLoot((int) preg_replace('/[^\d]/', '', $statistics->offsetGet(2)->textContent));

        $maxLife = $query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' pers-statistics ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' bars ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' life ')]")->current()->childNodes->item(2)->textContent;
        $maxLife = preg_replace('/[^\d]+/', '', $maxLife);

        $player->setMaxLife($maxLife);

        $items = [];
        for ($i = 0; $i < 9; $i++) {
            $e = $query->queryXpath("descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' slots ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' slot$i ')]/descendant::img")->current();

            if (!empty($e)) {
                $items[$i] = $e->attributes->getNamedItem('src')->textContent;
            } else {
                $items[$i] = null;
            }
        }

        $player->setItems($items);

        $player->setLastUpdate(new \DateTime());
        $player->setCoolness(
            $player->getHealth() + $player->getStrength() + $player->getDexterity() +
            $player->getResistance() + $player->getIntuition() + $player->getAttention() + $player->getCharism()
        );
    }

    #region Getters / Setters
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

    /**
     * @return \Zend\Http\Client
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client();
            $this->client->setAdapter('Zend\Http\Client\Adapter\Curl');
        }

        return $this->client;
    }
    #endregion
}