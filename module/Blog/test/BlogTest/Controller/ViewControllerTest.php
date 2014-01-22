<?php

namespace BlogTest\Controller;

use BlogTest\AbstractTestCase;
use Zend\Http\Request;
use Zend\Http\Response;

class ViewControllerTest extends AbstractTestCase
{
    public function testListActionAccessible()
    {
        $this->dispatch('/blog/list');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('Blog');
        $this->assertControllerName('Blog\Controller\View');
        $this->assertActionName('list');
        $this->assertControllerClass('ViewController');
        $this->assertMatchedRouteName('blog/list');
    }
}