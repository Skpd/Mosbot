<?php

namespace Runner\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Client;
use Zend\Http\Request;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $client = new Client('http://www.roswar.ru/');
        $client->setOptions(['useragent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) Chrome/27.0.1453.116 Safari/537.36']);

        $client->setMethod(Request::METHOD_POST);
        $client->setUri('http://www.roswar.ru/');
        $client->getRequest()->getPost()->fromArray(
            [
            'action'   => 'login',
            'email'    => $this->params('username'),
            ]
        );

        $password = $this->params('password');

        if (!empty($password)) {
            $client->getRequest()->getPost()->set('password', $password);
        }

        $response = $client->send();

        \Zend\Debug\Debug::dump($response->getBody());
    }
}