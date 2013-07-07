<?php

namespace Runner\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Client;
use Zend\Http\Request;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        /** @var \Runner\Service\Runner $runner */
        $runner = $this->serviceLocator->get('runner');

        $runner->login($this->params('username'), $this->params('password', null));

        $stats = [];

        $runner->digLogs(
            function ($content) use (&$stats) {
                preg_match_all('#Вы прокачали характеристику «<b>(.+)</b>».*<b>(.+)</b>.*tugriki.*([\d,]+)<#imsU', $content, $matches);
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $stats[$matches[1][$i]][$matches[2][$i]] = $matches[3][$i];
                }
            },
            strtotime('20130626')
        );

        foreach ($stats as $name => $stat) {
            ksort($stat);
            echo PHP_EOL . $name . PHP_EOL;
            foreach ($stat as $value => $price) {
                echo $value . "^x=" . (str_replace(',', '', $price)) . PHP_EOL;
            }
        }
    }
}