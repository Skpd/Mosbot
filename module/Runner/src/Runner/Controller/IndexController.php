<?php

namespace Runner\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Client;
use Zend\Http\Request;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $client = new Client('http://www.moswar.ru',
            ['adapter' => 'Zend\Http\Client\Adapter\Curl', 'maxredirects' => 1]
        );
        $client->send();

        $client->setMethod(Request::METHOD_POST);

        $client->getRequest()->getPost()->fromArray([
                                                    'action'   => 'login',
                                                    'email'    => $this->params('username'),
                                                    'password' => $this->params('password'),
                                                    'remember' => 'on',
                                                    ]);
        \Zend\Debug\Debug::dump($client->getRequest()->getPost()->toString());

//        $response = $client->dispatch();

        \Zend\Debug\Debug::dump($client->getRequest()->getPost()->toString());
//        \Zend\Debug\Debug::dump($response->getBody());
//        \Zend\Debug\Debug::dump($response->getStatusCode());
    }
}