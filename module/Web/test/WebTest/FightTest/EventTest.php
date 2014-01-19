<?php

namespace WebTest\FightTest;

use Runner\Document\Player;
use Web\Document\FightResult;
use Web\Document\PlayerStat;
use Web\Fight\Event;
use WebTest\Bootstrap;
use Zend\Dom\Query;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testClearDamage()
    {
        $event = new Event();

        $this->assertEquals(15, $event->clearDamage('asd-15-dsa'));
    }

    public function testClearNickname()
    {
        $event = new Event();

        $this->assertEquals('KING-KONG', $event->clearNickname('KING-KONG [11]'), 'space 2 digit');
        $this->assertEquals('KING-KONG', $event->clearNickname('KING-KONG [1]'), 'space 1 digit');
        $this->assertEquals('KING-KONG', $event->clearNickname('KING-KONG[1]'), 'no space 2 digit');
        $this->assertEquals('KING-KONG', $event->clearNickname('KING-KONG[11]'), 'no space 1 digit');
        $this->assertEquals('Yarfond', $event->clearNickname('Yarfond&nbsp;[11]'), 'stuff 2 digit');
        $this->assertEquals('Yarfond', $event->clearNickname('Yarfond&nbsp;[11]'), 'stuff 1 digit');
    }

    public function testGetPlayerByNickname()
    {
        $event = new Event();
        $result = new FightResult;
        $event->setResult($result);

        $player = new Player();
        $stat = new PlayerStat();
        $player->setNickname('engTest');
        $stat->setPlayer($player);
        $result->getPlayers()->add($stat);
        $this->assertEquals($stat, $event->getPlayerByNickname('engTest'));

        $player = new Player();
        $stat = new PlayerStat();
        $player->setNickname('русТест');
        $stat->setPlayer($player);
        $result->getPlayers()->add($stat);
        $this->assertEquals($stat, $event->getPlayerByNickname('русТест'));
    }

    public function testCreateNode()
    {
        $this->assertInstanceOf('DomNode', $this->createNode('<p>test</p>', true));
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        $event = new Event;

        $event->setResult($this->getFightResult());
        $event->setQuery($this->getPage());

        return $event;
    }

    public function getFightResult()
    {
        $result = new FightResult();

        $leftTeam = ["ТАМЕРЛАН", "Lioness", "Royka", "Иван-Дурак", "Esterlay", "Catalina", "Дракулито", "-Fate-", "Аллочка-людоедка", "Фантикус", "Burunduk", "001qwer100", "Dimkovich", "swedrad", "DJJonh", "оропрар", "ЗЛОБАЙ", "moon_shade", "ВАРЛ", "Няняшка", "Костоправ", "Михаил403", "КомуЧто", "Moonlightchild", "Yarfond", "Silvein", "Abishek", "Прелюдия", "НаноРобот", "Prometheus", "porky", "DESaa", "Ravanuza", "qooly", "коркор", "Tarantull", "Крысомаха Ярррр", "Крысомаха Арргх", "ВасяДыркин", "Цезарь", "Russianreagent", "Молодость", "enSES", "Агита", "Чертирожка", "qlp_1", "холодильник", "пивоедов", "Крысомаха Жжжжуть", "охохонюшки"];
        $rightTeam = ["Бугай", "Найс Норд", "lio666", "Металина", "Godfather", "ТАМБО", "5654754", "минимус", "Djamshut", "MeGaKiPiSH", "YricS", "Wiz", "Разная", "Сано1799", "Nyaaa", "koooooo", "Smo", "Бяк", "Владимир_Великий", "еФидрин", "Katrin0205", "Vladroman", "шввйв", "4r6t", "strongbow", "A1eG0", "бомбаняша", "gun_ur", "Леха ЛФК", "йотта", "мистер 007", "Хамза", "Toxxa1977", "Омоновец Братанов", "VannGogg", "Soth", "Итиан", "Шелест", "Cobain_Alkash", "ФЕД", "Даримка", "SaV", "Berry Betrayal", "DonRamon", "Фельер", "Крысомаха Ццццап", "Крысомаха Ххххвост", "Омоновец Собакин", "вадимкаоттуда", "belevaya", "Миланья483", "Музон", "zhenyaa", "крутой тиран"];

        foreach ($leftTeam as $k => $name) {
            $player = new Player();
            $player->setId($k);
            $player->setNickname($name);

            $stat = new PlayerStat();
            $stat->setPlayer($player);
            $stat->setTeam(PlayerStat::TEAM_LEFT);

            $result->getPlayers()->add($stat);
        }

        foreach ($rightTeam as $k => $name) {
            $player = new Player();
            $player->setId($k + sizeof($leftTeam));
            $player->setNickname($name);

            $stat = new PlayerStat();
            $stat->setPlayer($player);
            $stat->setTeam(PlayerStat::TEAM_LEFT);

            $result->getPlayers()->add($stat);
        }

        return $result;
    }

    public function createNode($htmlOrFilename, $fromText = false)
    {
        $document = new \DOMDocument('1.0', 'UTF-8');

        libxml_use_internal_errors(true);
        if ($fromText) {
            $document->loadHTML('<?xml encoding="UTF-8">' . $htmlOrFilename);
        } else {
            $document->loadHTML('<?xml encoding="UTF-8">' . file_get_contents(Bootstrap::getFixturePath() . $htmlOrFilename));
        }
        libxml_clear_errors();

        return $document->documentElement->firstChild->firstChild;
    }

    public function getPage()
    {
        return new Query(file_get_contents(Bootstrap::getFixturePath() . '/all-in-one.html'));
    }
}