<?php

namespace Web\Controller;

use DOMNode;
use Web\Fight\Exception\FightNotFoundException;
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

        $id = $this->params('id', null);

        /** @var \Zend\Form\Form $form */
        $form = $this->serviceLocator->get('FormElementManager')->get('FightStatsForm');

        if ($this->request->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();

                preg_match('#/fight/(\d+)#i', $data['url'], $m);
                $id = $m[1];
            }
        }

        if (!empty($id)) {
            $result = $this->getDocumentManager()->find('Web\Document\FightResult', $id);

            if (empty($result) || !$result->isFinished()) {
                try {
                    $result = $this->serviceLocator->get('FightAnalyzer')->analyze($id);
                } catch (FightNotFoundException $e) {
                    $result = null;
                    $view->setVariable('error', true);
                }
            }

            $view->setVariable('result', $result);
        }

        $view->setVariable('form', $form);
        $view->setVariable('id', $id);

        return $view;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->serviceLocator->get('doctrine.documentmanager.odm_default');
    }
}