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
        $contents = $this->getClient()->setUri($this->baseUrl . $player->getId())->send()->getBody();
        $query = new Query($contents);

        $alignment = $query->execute('.user i')->current();

        if (!empty($alignment)) {
            $player->setAlignment($alignment->attributes->getNamedItem('class')->textContent);
        } else {
            echo 'Nothing to do here ' . $player->getId() . PHP_EOL;
            return;
        }


        $player->setNickname($query->execute(".user a[href='/player/{$player->getId()}/']")->current()->textContent);
        $player->setLevel(str_replace(['[', ']'], '', $query->execute(".user .level")->current()->textContent));

        foreach ($query->execute('.stats .stat .num') as $k => $stat) {
            if ($k >= 7) {
                break;
            }

            /** @var $stat \DOMNode */
            $setter = 'set' . ucfirst($stat->parentNode->parentNode->attributes->getNamedItem('data-type')->textContent);

            if (method_exists($player, $setter)) {
                $player->{$setter}($stat->textContent);
            }
        }

        $statistics = $query->execute('.pers-statistics .numbers li');

        $player->setWins((int) preg_replace('/[^\d]/', '', $statistics->offsetGet(1)->textContent));
        $player->setLoot((int) preg_replace('/[^\d]/', '', $statistics->offsetGet(2)->textContent));

        $maxLife = $query->execute('.pers-statistics .bars .life')->current()->childNodes->item(2)->textContent;
        $maxLife = preg_replace('/[^\d]+/', '', $maxLife);

        $player->setMaxLife($maxLife);

        $player->setHaveRocket(strstr($contents, '/@/images/obj/rocket/proton.png') !== false);

        $dm = $this->serviceLocator->get('doctrine.documentmanager.odm_default');
        $dm->persist($player);
        $dm->flush();
        $dm->clear();

        echo 'Done ' . $player->getId() . ' (' . (microtime(1) - $t) . ' sec ' . memory_get_peak_usage(1) . ' mem)' .  PHP_EOL;
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