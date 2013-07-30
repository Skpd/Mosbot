<?php

namespace Web\Controller;

use DOMNode;
use Zend\Dom\Query;
use Zend\Http\ClientStatic;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return ['testString' => date(DATE_ATOM)];
    }

    public function fightStatsAction()
    {
        $view = new ViewModel();

        /** @var \Zend\Form\Form $form */
        $form = $this->serviceLocator->get('FormElementManager')->get('FightStatsForm');

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $result = [];

                $data  = $form->getData();
                $query = new Query(ClientStatic::get($data['url'])->getBody());

                $links = $query->execute('.pagescroll .block-rounded > *');

                foreach ($links as $pageLink) {
                    /** @var DomNode $pageLink */
                    $pageNumber = $pageLink->textContent;

                    if (is_numeric($pageNumber)) {
                        $link   = $data['url'] . '/' . $pageNumber;
                        $result = array_merge_recursive($result, $this->getStats($link));
                    }
                }

                foreach ($result as $player => $damages) {
                    foreach ($damages as $k => $v) {
                        if (!is_array($v)) {
                            unset($result[$player]);
                        } else if ($k == 'team') {
                            $result[$player][$k] = current($v);
                        } else if (isset($result[$player])) {
                            $result[$player][$k] = array_sum($v);
                        }
                    }

                    if (isset($result[$player])) {
                        $result[$player]['sum'] = $result[$player]['enemy'] + $result[$player]['pet-enemy'];
                    }
                }

                uasort(
                    $result,
                    function ($a, $b) {
                        if ($a['sum'] == $b['sum']) {
                            return 0;
                        }
                        return ($a['sum'] < $b['sum']) ? 1 : -1;
                    }
                );

                $teams = ['Левые' => 0, 'Правые' => 0];

                foreach ($result as $damages) {
                    $teams[$damages['team']] += $damages['sum'];
                }

                $view->setVariable('teams', $teams);
                $view->setVariable('result', $result);
            }
        }

        return $view->setVariable('form', $form);
    }

    private function getStats($link)
    {
        $result  = [];
        $players = [];

        $query = new Query(ClientStatic::get($link)->getBody());

        for ($i = 1; $i < 3; $i++) {
            $items = $query->queryXpath(
                "descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' group ')][" . $i
                . "]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' list-users ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' user ')]/descendant::a[last()]/text()"
            );
            foreach ($items as $e) {
                /** @var \DOMText $e */
                $parent = $e->parentNode->parentNode->parentNode;
                if ($parent->lastChild->nodeType != 3) {
                    $pet = implode(' ', array_slice(explode(' ', $parent->lastChild->attributes->getNamedItem('title')->textContent), 0, -1));
                } else {
                    $pet = null;
                }

                $players[$e->textContent] = ['name' => $e->textContent, 'pet' => $pet, 'team' => $i == 2 ? 'Правые' : 'Левые'];
                $result[$e->textContent]  = ['ally' => 0, 'enemy' => 0, 'kills' => 0, 'pet-pet' => 0, 'pet-enemy' => 0, 'team' => $i == 2 ? 'Правые' : 'Левые'];
            }
        }

        $actions = $query->execute('.fight-log .text p');

        $attacker = null;

        foreach ($actions as $action) {
            /** @var DomNode $action */
            if ($action->childNodes->length == 0 || $action->attributes->getNamedItem('class')->textContent == 'line'
                || $action->childNodes->length == 6
                || $action->childNodes->length == 7
                || $action->lastChild->textContent == ' сохраняет флаг'
            ) {
                continue;
            }

            if ($action->attributes->getNamedItem('class')->textContent == 'bang-throw ') {
                $attacker = $this->clearNick($action->firstChild->textContent);
                continue;
            }

            if ($action->attributes->getNamedItem('class')->textContent == 'bang ') {
                $damage = preg_replace('/[^\d]+/', '', $action->lastChild->textContent);
                $victim = $this->clearNick($action->childNodes->item(1)->textContent);

                if ($players[$victim]['team'] == $players[$attacker]['team']) {
                    $result[$attacker]['ally'] += $damage;
                } else {
                    $result[$attacker]['enemy'] += $damage;
                }

                continue;
            }

            if ($action->attributes->getNamedItem('class')->textContent == 'killed ') {
                if ($action->lastChild->attributes->getNamedItem('class')->textContent != 'name-neutral') {
                    $attacker = $this->clearNick($action->firstChild->textContent);
                    if (isset($result[$attacker])) {
                        $result[$attacker]['kills']++;
                    }

                }
                continue;
            }

            $haveSerial = $action->firstChild->attributes->getNamedItem('class')->textContent == 'icon serial';
            if ($action->childNodes->item(1 + $haveSerial)->attributes->getNamedItem('class')->textContent == 'punch') {
                $attacker = $action->childNodes->item(0 + $haveSerial)->textContent;
                $damage   = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $action->lastChild->textContent);
                $victim   = $action->childNodes->item(2 + $haveSerial)->textContent;

                if (!preg_match('/\[\d+\]/', $attacker)) {
                    if ($action->previousSibling->firstChild->attributes->getNamedItem('class')->textContent == 'icon serial') {
                        $attacker = $this->clearNick($action->previousSibling->childNodes->item(1)->textContent);
                    } else {
                        $attacker = $this->clearNick($action->previousSibling->firstChild->textContent);
                    }

                    if (!preg_match('/\[\d+\]/', $victim)) {
                        $result[$attacker]['pet-pet'] = $damage;
                    } else {
                        $result[$attacker]['pet-enemy'] = $damage;
                    }
                } else {
                    $attacker                   = $this->clearNick($attacker);
                    $result[$attacker]['enemy'] = $damage;
                }
            }
        }
//\Zend\Debug\Debug::dump($result);exit;
        return $result;
    }

    private function clearNick($nick)
    {
        return substr($nick, 0, strpos($nick, '[') - 2);
    }
}