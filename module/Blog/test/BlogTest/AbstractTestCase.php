<?php

namespace BlogTest;

use Doctrine\ODM\MongoDB\DocumentManager;
use Zend\Test\PHPUnit\Controller\AbstractControllerTestCase;

class AbstractTestCase extends AbstractControllerTestCase
{
    protected function setUp()
    {
        $this->setApplicationConfig(include __DIR__ . '/../TestConfiguration.php');
        parent::setUp();

        /** @var DocumentManager $dm */
        $dm = $this->getApplication()->getServiceManager()->get('blog_odm');
        $db = $this->getApplication()->getServiceManager()->get('Config')['doctrine']['configuration']['odm_default']['default_db'];

        $dm->getConnection()->connect();

        $dm->getConnection()->getMongo()->dropDB($db);
    }

    public function testOdmConnectedToTheTestDb()
    {
        /** @var DocumentManager $dm */
        $dm = $this->getApplication()->getServiceManager()->get('blog_odm');
        $db = $this->getApplication()->getServiceManager()->get('Config')['doctrine']['configuration']['odm_default']['default_db'];

        $this->assertTrue(strstr($dm->getConnection()->getServer(), $db) !== false);
    }
}