<?php

namespace Runner\Controller;

use MongoCursorException;
use Runner\Document\Proxy;
use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;

class ProxyController extends AbstractActionController
{
    private $documentManager;

    public function checkAction()
    {
        $client = new Client('http://wtfismyip.com/text');
        $client->setAdapter('Zend\Http\Client\Adapter\Proxy');

        foreach ($this->getDocumentManager()->getRepository('Runner\Document\Proxy')->findAll() as $proxy) {
            /** @var $proxy Proxy */
            printf("Checking proxy %s:%u ...\n", $proxy->getIp(), $proxy->getPort());

            $expectedResponse = $proxy->getIp();

            $client->getAdapter()->setOptions(['proxy_host' => $proxy->getIp(), 'proxy_port' => $proxy->getPort()]);
//            $client->setMethod('POST');

            try {
                $startTime = microtime(1);
                $response  = $client->send();
                $proxy->setSpeed(microtime(1) - $startTime);
                if ($expectedResponse == trim($response->getBody()) || preg_match('#^\d+\.\d+\.\d+\.\d+#', trim($response->getBody()))) {
                    $proxy->setSucceedChecks($proxy->getSucceedChecks() + 1);
                } else {
                    \Zend\Debug\Debug::dump(trim($response->getBody()));

                    $proxy->setFailedChecks($proxy->getFailedChecks() + 1);
                }
            } catch (\Exception $e) {
                printf("\tFailed to connect (%s)\n", $e->getMessage());
                $proxy->setFailedChecks($proxy->getFailedChecks() + 1);
            }

            $this->getDocumentManager()->persist($proxy);
        }

        $this->getDocumentManager()->flush();
    }

    public function gatherAction()
    {
//        $list = file_get_contents("http://freedailyproxy.ru/");
        $list = file_get_contents("http://www.cool-tests.com/Russian-Federation-proxy.php");
        preg_match_all('#(\d+\.\d+\.\d+\.\d+):(\d+)#i', $list, $matches, PREG_SET_ORDER);

        foreach ($matches as $row) {
//            list($ip, $port) = explode(':', $row);
            $ip   = $row[1];
            $port = $row[2];

            $proxy = new Proxy();
            $proxy->setIp($ip);
            $proxy->setPort($port);

            $this->getDocumentManager()->persist($proxy);
            try {
                $this->getDocumentManager()->flush();
            } catch (MongoCursorException $e) {
                continue;
            }
        }
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager()
    {
        if (null === $this->documentManager) {
            $this->documentManager = $this->serviceLocator->get('doctrine.documentmanager.odm_default');
        }

        return $this->documentManager;
    }
}