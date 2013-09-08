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
                $data = $form->getData();

                preg_match('#/fight/(\d+)#i', $data['url'], $m);
                $id = $m[1];

                $result = $this->getDocumentManager()->find('Web\Document\FightResult', $id);

                if (empty($result)) {
                    $result = $this->serviceLocator->get('FightAnalyzer')->analyze($id);
                }

                $view->setVariable('result', $result);
            }
        }

        $view->setVariable('form', $form);

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