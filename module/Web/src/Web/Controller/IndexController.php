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

                $data = $form->getData();
                $query = new Query(ClientStatic::get($data['url'])->getBody());

                $links = $query->execute('.pagescroll .block-rounded > *');

                foreach ($links as $pageLink) {
                    /** @var DomNode $pageLink */
                    $pageNumber = $pageLink->textContent;

                    if (is_numeric($pageNumber)) {
                        $link = $data['url'] . '/' . $pageNumber;
                        $result = array_merge_recursive($result, $this->getStats($link));
                    }
                }

                $result = array_map('array_sum', $result);

                arsort($result);

                $view->setVariable('result', $result);
            }
        }

        return $view->setVariable('form', $form);
    }

    private function getStats($link)
    {
        $result = [];

        $query = new Query(ClientStatic::get($link)->getBody());

        $punches = $query->execute('.text .punch');

        foreach ($punches as $punch) {
            /** @var DomNode $punch */
            $attacker = $punch->previousSibling->textContent;
            $damage   = str_replace(['(', ')', ' ', '-'], ['', '', '', ''], $punch->nextSibling->nextSibling->textContent);
            $isPet = !preg_match('/\[\d+\]/', $attacker);

            if (!$isPet) {
                $result[$attacker][] = $damage;
            }
        }

        return $result;
    }
}