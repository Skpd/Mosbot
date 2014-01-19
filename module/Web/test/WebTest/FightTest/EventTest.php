<?php

namespace WebTest\FightTest;

use Runner\Document\Player;
use Web\Document\FightResult;
use Web\Document\PlayerStat;
use Web\Fight\Event;
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
        $this->assertInstanceOf('DomNode', $this->createNode('<p>test</p>'));
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

    public function createNode($html)
    {
        $document = new \DOMDocument('1.0');
        $document->loadHTML('<?xml encoding="UTF-8">' . $html);

        return $document->documentElement->firstChild->firstChild;
    }

    public function getPage()
    {
        $html = <<<HTML
<div class="text"><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-resident"><b>enSES&nbsp;[10]</b></span> использует <b>Стальной щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-arrived"><b>DonRamon&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-arrived"><b>Сано1799&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-arrived"><b>еФидрин&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="heal "><span class="icon icon-heal"></span><span class="name-arrived"><b>крутой тиран&nbsp;[8]</b></span> кушает <b>Пицца «Пепперони»</b> (+700)</p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-arrived"><b>вадимкаоттуда&nbsp;[10]</b></span> использует <b>Щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-resident"><b>Няняшка&nbsp;[11]</b></span> использует <b>Стальной щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-resident"><b>porky&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="heal "><span class="icon icon-heal"></span><span class="name-resident"><b>Esterlay&nbsp;[11]</b></span> кушает <b>Дуриан «Заморский»</b> (+2491)</p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-resident"><b>НаноРобот&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="heal "><span class="icon icon-heal"></span><span class="name-arrived"><b>Cobain_Alkash&nbsp;[11]</b></span> кушает <b>Дуриан «Заморский»</b> (+2491)</p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-arrived"><b>Найс Норд&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-resident"><b>оропрар&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-arrived"><b>gun_ur&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-resident"><b>Yarfond&nbsp;[11]</b></span> использует <b>Стальной щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-resident"><b>ВАРЛ&nbsp;[11]</b></span> использует <b>Стальной щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-resident"><b>Ravanuza&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-resident"><b>Костоправ&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="antigranata "><span class="icon icon-antigranata"></span><span class="name-arrived"><b>5654754&nbsp;[11]</b></span> использует <b>Щит «Ультра»</b></p><p class="bang-poison "><span class="icon icon-bang-poison"></span><span class="name-arrived"><b>Djamshut&nbsp;[11]</b></span> получает отравление дымом  (–150)</p><p class="bang-poison "><span class="icon icon-bang-poison"></span><span class="name-arrived"><b>lio666&nbsp;[11]</b></span> получает отравление дымом  (–150)</p><p class="bang-poison "><span class="icon icon-bang-poison"></span><span class="name-arrived"><b>strongbow&nbsp;[11]</b></span> получает отравление дымом  (–150)</p><p class="bang-throw "><span class="name-arrived"><b>SaV&nbsp;[11]</b></span> метает в толпу дерущихся <b>Граната «Морская»</b></p><p class="bang "><span class="icon icon-bang"></span><span class="name-resident"><b>Yarfond&nbsp;[11]</b></span> получает урон от взрыва  (–188)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-resident"><b>Silvein&nbsp;[11]</b></span> получает урон от взрыва  (–182)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-resident"><b>ВАРЛ&nbsp;[11]</b></span> получает урон от взрыва  (–188)</p><p class="bang-throw "><span class="name-resident"><b>Иван-Дурак&nbsp;[11]</b></span> метает в толпу дерущихся <b>Бомба-вонючка</b></p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>gun_ur&nbsp;[11]</b></span> получает урон от взрыва  (–315)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>крутой тиран&nbsp;[8]</b></span> получает урон от взрыва  (–900)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Katrin0205&nbsp;[11]</b></span> получает урон от взрыва  (–900)</p><p class="bang-throw "><span class="name-resident"><b>Молодость&nbsp;[10]</b></span> метает в толпу дерущихся <b>Бомба-вонючка</b></p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Djamshut&nbsp;[11]</b></span> получает урон от взрыва  (–92)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Cobain_Alkash&nbsp;[11]</b></span> получает урон от взрыва  (–698)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>A1eG0&nbsp;[11]</b></span> получает урон от взрыва  (–101)</p><p class="bang-throw "><span class="name-resident"><b>Прелюдия&nbsp;[11]</b></span> метает в толпу дерущихся <b>Граната «Морская»</b></p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Леха ЛФК&nbsp;[11]</b></span> получает урон от взрыва  (–1250)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Wiz&nbsp;[11]</b></span> получает урон от взрыва  (–1250)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Итиан&nbsp;[11]</b></span> получает урон от взрыва  (–1250)</p><p class="bang-throw "><span class="name-resident"><b>DJJonh&nbsp;[11]</b></span> метает в толпу дерущихся <b>Бомба «Кузькина мать»</b></p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Леха ЛФК&nbsp;[11]</b></span> получает урон от взрыва  (–1000)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>koooooo&nbsp;[11]</b></span> получает урон от взрыва  (–1000)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Katrin0205&nbsp;[11]</b></span> получает урон от взрыва  (–1000)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>5654754&nbsp;[11]</b></span> получает урон от взрыва  (–350)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>еФидрин&nbsp;[11]</b></span> получает урон от взрыва  (–350)</p><p class="bang-throw "><span class="name-resident"><b>Михаил403&nbsp;[11]</b></span> метает в толпу дерущихся <b>Бомба «Кузькина мать»</b></p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Фельер&nbsp;[11]</b></span> получает урон от взрыва  (–1000)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>strongbow&nbsp;[11]</b></span> получает урон от взрыва  (–150)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>вадимкаоттуда&nbsp;[10]</b></span> получает урон от взрыва  (–323)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>lio666&nbsp;[11]</b></span> получает урон от взрыва  (–933)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>ТАМБО&nbsp;[11]</b></span> получает урон от взрыва  (–350)</p><p class="bang-throw "><span class="name-resident"><b>ТАМЕРЛАН&nbsp;[11]</b></span> метает в толпу дерущихся <b>Граната «Морская»</b></p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Djamshut&nbsp;[11]</b></span> получает урон от взрыва  (–188)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>йотта&nbsp;[11]</b></span> получает урон от взрыва  (–1250)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Разная&nbsp;[11]</b></span> получает урон от взрыва  (–438)</p><p class="bang-throw "><span class="name-resident"><b>Catalina&nbsp;[11]</b></span> метает в толпу дерущихся <b>Граната «Морская»</b></p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>strongbow&nbsp;[11]</b></span> получает урон от взрыва  (–188)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>belevaya&nbsp;[9]</b></span> получает урон от взрыва  (–1250)</p><p class="bang "><span class="icon icon-bang"></span><span class="name-arrived"><b>Металина&nbsp;[11]</b></span> получает урон от взрыва  (–1250)</p><p class="helmet "><span class="icon icon-helmet"></span><span class="name-arrived"><b>Металина&nbsp;[11]</b></span> использует предмет <b>Энергетическая вода</b></p><p class="helmet "><span class="icon icon-helmet"></span><span class="name-resident"><b>Чертирожка&nbsp;[10]</b></span> использует предмет <b>Энергетическая вода</b></p><p class="helmet "><span class="icon icon-helmet"></span><span class="name-resident"><b>swedrad&nbsp;[11]</b></span> использует предмет <b>Энергетическая вода</b></p><p class="reflect "><span class="icon icon-reflect"></span><span class="name-arrived"><b>Шелест&nbsp;[11]</b></span> готовится защищаться предметом <b>Коварная пружина</b></p><p class="helmet "><span class="icon icon-helmet"></span><span class="name-arrived"><b>Katrin0205&nbsp;[11]</b></span> использует предмет <b>Энергетическая вода</b></p><p class="reflect "><span class="icon icon-reflect"></span><span class="name-resident"><b>Esterlay&nbsp;[11]</b></span> готовится защищаться предметом <b>Дуриан «Заморский»</b></p><p class="helmet "><span class="icon icon-helmet"></span><span class="name-resident"><b>Аллочка-людоедка&nbsp;[11]</b></span> использует предмет <b>Энергетическая вода</b></p><p class="reflect "><span class="icon icon-reflect"></span><span class="name-arrived"><b>Cobain_Alkash&nbsp;[11]</b></span> готовится защищаться предметом <b>Дуриан «Заморский»</b></p><p class="helmet "><span class="icon icon-helmet"></span><span class="name-resident"><b>-Fate-&nbsp;[11]</b></span> использует предмет <b>Энергетическая вода</b></p><p class="helmet "><span class="icon icon-helmet"></span><span class="name-arrived"><b>Итиан&nbsp;[11]</b></span> использует предмет <b>Пробковая каска</b></p><p class="helmet "><span class="icon icon-helmet"></span><span class="name-resident"><b>Фантикус&nbsp;[11]</b></span> использует предмет <b>Энергетическая вода</b></p><p class="cheese "><span class="icon icon-cheese"></span><span class="name-resident"><b>001qwer100&nbsp;[11]</b></span> приманивает Крысомаху <b>Ароматным сыром</b></p><p class=""><span class="name-arrived"><b>Бяк&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-resident"><b>001qwer100&nbsp;[11]</b></span> (-689)</p><p class="critical "><span class="name-arrived">Овчарка "кто не с нами тот под нами"</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Киска "попала в рабство"</span> (-1727)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-resident"><b>moon_shade&nbsp;[11]</b></span>
										бьет
										<span class="name-arrived"><b>Итиан&nbsp;[11]</b></span>
										, но
										<span class="name-arrived"><b>Итиан&nbsp;[11]</b></span><span class="helmethit">защищен от удара</span></p><p class=""><span class="name-resident">Ротвейлер "Адель"</span><span class="punch">прицеливается</span> и нападает на игрока&nbsp;<span class="name-arrived"><b>Итиан&nbsp;[11]</b></span> (-1)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>ТАМБО&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-resident"><b>Lioness&nbsp;[11]</b></span> (-8538)
								, <span tooltip="1" style="cursor:help;"><span class="spike-marked">но царапается</span> о&nbsp;шипы <span class="spike-injury"><i class="icon icon-spike"></i>-171</span><i class="question-icon-small"></i></span></p><p class="critical "><span class="name-arrived">Ротвейлер "Daff"</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Овчарка</span> (-3386)</p><p class="line"><spacer></spacer></p><p></p><p class="critical "><span class="name-resident"><b>Lioness&nbsp;[11]</b></span><span class="punch strong" tooltip="1">
												бьёт
											</span><span class="name-arrived"><b>крутой тиран&nbsp;[8]</b></span> (-1063)</p><p class="critical "><span class="name-resident">Овчарка</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-arrived">Чихуа-хуа "Абдурахман Эль Хасан Хазрет II"</span> (-905)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-resident"><b>ЗЛОБАЙ&nbsp;[11]</b></span><span class="miss">не попадает по</span><span class="name-arrived"><b>Металина&nbsp;[11]</b></span></p><p class="critical "><span class="name-resident">Ротвейлер "БУРЖУЙ"</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-arrived">Киска "Фифа"</span> (-1195)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-resident"><b>охохонюшки&nbsp;[9]</b></span><span class="miss">не попадает по</span><span class="name-arrived"><b>ТАМБО&nbsp;[11]</b></span></p><p class=""><span class="name-resident">Чихуа-хуа</span><span class="miss">не попадает по</span><span class="name-arrived">Ротвейлер "Daff"</span></p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-resident"><b>DESaa&nbsp;[11]</b></span><span class="miss">не попадает по</span><span class="name-arrived"><b>крутой тиран&nbsp;[8]</b></span></p><p class=""><span class="icon serial">2</span><span class="name-resident">Киска "Маиска"</span><span class="punch">прицеливается</span> и нападает на игрока&nbsp;<span class="name-arrived"><b>крутой тиран&nbsp;[8]</b></span> (-569)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>Шелест&nbsp;[11]</b></span><span class="reflected">отпружинивает удар</span><span class="name-resident"><b>Abishek&nbsp;[11]</b></span> (-2911)</p><p class="critical "><span class="name-resident">Доберман</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-arrived">Попугай</span> (-1728)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="icon serial">2</span><span class="name-arrived"><b>Леха ЛФК&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-resident"><b>001qwer100&nbsp;[11]</b></span> (-784)</p><p class="critical "><span class="icon serial">2</span><span class="name-arrived">Доберман "СИГУРД"</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Киска "попала в рабство"</span> (-1511)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>A1eG0&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-resident"><b>Catalina&nbsp;[11]</b></span> (-995)
								, <span tooltip="1" style="cursor:help;"><span class="spike-marked">но царапается</span> о&nbsp;шипы <span class="spike-injury"><i class="icon icon-spike"></i>-20</span><i class="question-icon-small"></i></span></p><p class="critical "><span class="name-arrived">Киска "Бегемот"</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Овчарка "Dot"</span> (-1441)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-resident"><b>Prometheus&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-arrived"><b>вадимкаоттуда&nbsp;[10]</b></span> (-1546)</p><p class="critical "><span class="name-resident">Ротвейлер</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-arrived">Ротвейлер "кент"</span> (-2147)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>Разная&nbsp;[11]</b></span>
										бьет
										<span class="name-resident"><b>-Fate-&nbsp;[11]</b></span>
										, но
										<span class="name-resident"><b>-Fate-&nbsp;[11]</b></span><span class="helmethit">защищен от удара</span></p><p class="critical "><span class="name-arrived">Ротвейлер</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Киска "Матроскин"</span> (-2571)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>Djamshut&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-resident"><b>DJJonh&nbsp;[11]</b></span> (-673)
								, <span tooltip="1" style="cursor:help;"><span class="spike-marked">но царапается</span> о&nbsp;шипы <span class="spike-injury"><i class="icon icon-spike"></i>-14</span><i class="question-icon-small"></i></span></p><p class=""><span class="name-arrived">Киска "Царапыч"</span><span class="punch">
												бьёт
											</span><span class="name-resident">Киска "Adolf Hitler"</span> (-862)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>мистер 007&nbsp;[11]</b></span>
										бьет
										<span class="name-resident"><b>swedrad&nbsp;[11]</b></span>
										, но
										<span class="name-resident"><b>swedrad&nbsp;[11]</b></span><span class="helmethit">защищен от удара</span></p><p class=""><span class="name-arrived">Киска "Барсик"</span><span class="punch">
												бьёт
											</span><span class="name-resident">Киска "Мокрая"</span> (-286)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="icon serial">2</span><span class="name-arrived"><b>Toxxa1977&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-resident"><b>Lioness&nbsp;[11]</b></span> (-7590)
								, <span tooltip="1" style="cursor:help;"><span class="spike-marked">но царапается</span> о&nbsp;шипы <span class="spike-injury"><i class="icon icon-spike"></i>-152</span><i class="question-icon-small"></i></span></p><p class="critical "><span class="icon serial">2</span><span class="name-arrived">Ротвейлер "Злобрячка"</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Овчарка</span> (-3092)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="icon serial">2</span><span class="name-arrived"><b>Фельер&nbsp;[11]</b></span>
										бьет
										<span class="name-resident"><b>swedrad&nbsp;[11]</b></span>
										, но
										<span class="name-resident"><b>swedrad&nbsp;[11]</b></span><span class="helmethit">защищен от удара</span></p><p class="critical "><span class="icon serial">2</span><span class="name-arrived">Доберман "Жора"</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Киска "Мокрая"</span> (-1681)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>MeGaKiPiSH&nbsp;[11]</b></span><span class="miss">не попадает по</span><span class="name-resident"><b>Lioness&nbsp;[11]</b></span></p><p class="critical "><span class="icon serial">3</span><span class="name-arrived">Киска "ЛаСТиК"</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Овчарка</span> (-1275)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>йотта&nbsp;[11]</b></span>
										бьет
										<span class="name-resident"><b>Аллочка-людоедка&nbsp;[11]</b></span>
										, но
										<span class="name-resident"><b>Аллочка-людоедка&nbsp;[11]</b></span><span class="helmethit">защищен от удара</span></p><p class="critical "><span class="name-arrived">Доберман</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Киска "Мясоед"</span> (-713)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>Wiz&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-resident"><b>ТАМЕРЛАН&nbsp;[11]</b></span> (-6122)
								, <span tooltip="1" style="cursor:help;"><span class="spike-marked">но царапается</span> о&nbsp;шипы <span class="spike-injury"><i class="icon icon-spike"></i>-123</span><i class="question-icon-small"></i></span></p><p class=""><span class="name-arrived">Киска "Шушуня"</span><span class="punch">
												бьёт
											</span><span class="name-resident">Доберман "Одуванчик"</span> (-1026)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-resident"><b>qooly&nbsp;[11]</b></span><span class="miss">не попадает по</span><span class="name-arrived"><b>Шелест&nbsp;[11]</b></span></p><p class=""><span class="name-resident">Доберман "dooby"</span><span class="punch">прицеливается</span> и нападает на игрока&nbsp;<span class="name-arrived"><b>Шелест&nbsp;[11]</b></span> (-2)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="icon serial">2</span><span class="name-arrived"><b>lio666&nbsp;[11]</b></span>
										бьет
										<span class="name-resident"><b>Аллочка-людоедка&nbsp;[11]</b></span>
										, но
										<span class="name-resident"><b>Аллочка-людоедка&nbsp;[11]</b></span><span class="helmethit">защищен от удара</span></p><p class=""><span class="icon serial">2</span><span class="name-arrived">Ротвейлер "Пипец"</span><span class="punch">
												бьёт
											</span><span class="name-resident">Киска "Мясоед"</span> (-1776)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>Vladroman&nbsp;[11]</b></span><span class="miss">не попадает по</span><span class="name-resident"><b>охохонюшки&nbsp;[9]</b></span></p><p class="critical "><span class="name-arrived">Ротвейлер "Рэкс"</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Чихуа-хуа</span> (-1053)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>koooooo&nbsp;[11]</b></span>
										бьет
										<span class="name-resident"><b>Костоправ&nbsp;[11]</b></span>
										, но
										<span class="name-resident"><b>Костоправ&nbsp;[11]</b></span><span class="helmethit">защищен от удара</span></p><p class=""><span class="name-arrived">Киска "гном"</span><span class="miss">не попадает по</span><span class="name-resident">Ротвейлер "Пони"</span></p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-resident"><b>Silvein&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-arrived"><b>мистер 007&nbsp;[11]</b></span> (-1234)
								, <span tooltip="1" style="cursor:help;"><span class="spike-marked">но царапается</span> о&nbsp;шипы <span class="spike-injury"><i class="icon icon-spike"></i>-50</span><i class="question-icon-small"></i></span></p><p class=""><span class="name-resident">Киска "Бальзамчик"</span><span class="miss">не попадает по</span><span class="name-arrived">Киска "Барсик"</span></p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>VannGogg&nbsp;[10]</b></span><span class="miss">не попадает по</span><span class="name-resident"><b>swedrad&nbsp;[11]</b></span></p><p class="critical "><span class="icon serial">3</span><span class="name-arrived">Ротвейлер</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Киска "Мокрая"</span> (-1875)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-resident"><b>Moonlightchild&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-arrived"><b>Бяк&nbsp;[11]</b></span> (-598)
								, <span tooltip="1" style="cursor:help;"><span class="spike-marked">но царапается</span> о&nbsp;шипы <span class="spike-injury"><i class="icon icon-spike"></i>-96</span><i class="question-icon-small"></i></span></p><p class=""><span class="name-resident">Киска "Муррррлыка"</span><span class="punch">
												бьёт
											</span><span class="name-arrived">Овчарка "кто не с нами тот под нами"</span> (-250)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>Godfather&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-resident"><b>оропрар&nbsp;[11]</b></span> (-339)
								, <span tooltip="1" style="cursor:help;"><span class="spike-marked">но царапается</span> о&nbsp;шипы <span class="spike-injury"><i class="icon icon-spike"></i>-68</span><i class="question-icon-small"></i></span></p><p class="critical "><span class="name-arrived">Овчарка "Альберт Нери"</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Ротвейлер "Сухенькая Кисонька"</span> (-413)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="icon serial">2</span><span class="name-arrived"><b>бомбаняша&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-resident"><b>охохонюшки&nbsp;[9]</b></span> (-4871)
								, <span tooltip="1" style="cursor:help;"><span class="spike-marked">но царапается</span> о&nbsp;шипы <span class="spike-injury"><i class="icon icon-spike"></i>-98</span><i class="question-icon-small"></i></span></p><p class="critical "><span class="icon serial">2</span><span class="name-arrived">Ротвейлер</span><span class="punch" tooltip="1">
												бьёт
											</span><span class="name-resident">Чихуа-хуа</span> (-3989)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-arrived"><b>strongbow&nbsp;[11]</b></span><span class="punch">
												бьёт
											</span><span class="name-resident"><b>Няняшка&nbsp;[11]</b></span> (-653)
								, <span tooltip="1" style="cursor:help;"><span class="spike-marked">но царапается</span> о&nbsp;шипы <span class="spike-injury"><i class="icon icon-spike"></i>-40</span><i class="question-icon-small"></i></span></p><p class=""><span class="name-arrived">Доберман "намбэр ван"</span><span class="punch">
												бьёт
											</span><span class="name-resident">Киска "Нямка"</span> (-257)</p><p class="line"><spacer></spacer></p><p></p><p class=""><span class="name-resident"><b>ВасяДыркин&nbsp;[10]</b></span><span class="miss">не попадает по</span><span class="name-arrived"><b>Разная&nbsp;[11]</b></span></p><p class=""><span class="name-resident">Доберман "Эрл"</span><span class="miss">не попадает по</span><span class="name-arrived">Ротвейлер</span></p><p class="line"><spacer></spacer></p><p></p><p class="critical "><span class="name-arrived"><b>Soth&nbsp;[10]</b></span><span class="punch strong" tooltip="1">
												бьёт
											</span><span class="name-neutral"><b>Крысомаха Ярррр&nbsp;[11]</b></span> (-5511)</p><p class=""><span class="name-arrived">Овчарка "Мухтар"</span><span class="punch">
												бьёт
											</span><span class="name-neutral"><b>Крысомаха Ярррр&nbsp;[11]</b></span> (-510)</p><p class="line"><spacer></spacer></p><p></p><p class="forcejoin "><span class="icon icon-forcejoin"></span><span class="name-arrived"><b>Даримка&nbsp;[11]</b></span> вмешивается в бой</p><p class="forcejoin "><span class="icon icon-forcejoin"></span><span class="name-arrived"><b>belevaya&nbsp;[9]</b></span> вмешивается в бой</p><p class="killed "><span class="name-arrived">Ротвейлер</span> убивает <span class="name-resident">Чихуа-хуа</span></p><p class="killed "><span class="name-arrived"><b>ТАМБО&nbsp;[11]</b></span> убивает <span class="name-resident"><b>Lioness&nbsp;[11]</b></span></p><p class="killed "><span class="name-resident"><b>Lioness&nbsp;[11]</b></span> убивает <span class="name-arrived"><b>крутой тиран&nbsp;[8]</b></span></p><p class="killed "><span class="name-arrived"><b>Wiz&nbsp;[11]</b></span> убивает <span class="name-resident"><b>ТАМЕРЛАН&nbsp;[11]</b></span></p><p class="killed "><span class="name-arrived"><b>бомбаняша&nbsp;[11]</b></span> убивает <span class="name-resident"><b>охохонюшки&nbsp;[9]</b></span></p><p class="killed "><span class="name-arrived"><b>Soth&nbsp;[10]</b></span> убивает <span class="name-neutral"><b>Крысомаха Ярррр&nbsp;[11]</b></span></p>
</div>
HTML;
        return new Query($html);
    }
}