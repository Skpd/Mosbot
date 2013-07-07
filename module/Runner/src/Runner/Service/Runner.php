<?php

namespace Runner\Service;

use Zend\Http\Client;

class Runner
{
    private $baseUrl = 'http://www.roswar.ru/';
    private $client;

    public function __construct()
    {
        $this->client = new Client($this->baseUrl);
        $this->client->setOptions(['useragent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) Chrome/27.0.1453.116 Safari/537.36']);
    }

    public function login($email, $password = null)
    {
        $this->client->resetParameters();

        $this->client->setMethod('POST');
        $this->client->setUri($this->baseUrl);
        $this->client->getRequest()->getPost()
            ->fromArray(
                [
                'action' => 'login',
                'email'  => $email,
                ]
            );

        if (!empty($password)) {
            $this->client->getRequest()->getPost()->set('password', $password);
        }

        $this->client->send();
    }

    public function digLogs($callback, $limit)
    {
        if (!is_callable($callback)) {
            throw new \RuntimeException('Callback is not callable.');
        }
        $this->client->resetParameters();

        $current = time();

        while ($current > $limit) {
            $this->client->setUri($this->baseUrl . 'phone/logs/' . date('Ymd', $current));
            $content = $this->client->send()->getBody();
            $callback($content);

            $page = 2;
            while (strstr($content, '→') !== false) {
                $this->client->setUri($this->baseUrl . 'phone/logs/' . date('Ymd', $current) . '/' . $page++);
                $content = $this->client->send()->getBody();
                $callback($content);
            }

            $current -= 86400;
        }
    }
}