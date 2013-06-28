<?php

namespace Web\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return ['testString' => date(DATE_ATOM)];
    }
}