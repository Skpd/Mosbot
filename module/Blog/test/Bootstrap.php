<?php
/**
 * @author Marco Pivetta <ocramius@gmail.com>
 */

namespace BlogTest;

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\Loader\StandardAutoloader;
use Zend\Loader\AutoloaderFactory;
use \RuntimeException;
use Zend\Test\Util\ModuleLoader;

chdir(__DIR__);

$previousDir = '.';

while (!file_exists('config/application.config.php')) {
    $dir = dirname(getcwd());

    if ($previousDir === $dir) {
        throw new RuntimeException(
            'Unable to locate "config/application.config.php":'
            . ' is the Content module in a sub-directory of your application skeleton?'
        );
    }

    $previousDir = $dir;
    chdir($dir);
}

include_once 'vendor/autoload.php';

$config = include_once 'TestConfiguration.php';

include 'vendor/zendframework/zendframework/library/Zend/Loader/AutoloaderFactory.php';
AutoloaderFactory::factory(array(
    'Zend\Loader\StandardAutoloader' => array(
        'autoregister_zf' => true,
        'namespaces' => array(
            __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
        ),
    ),
));

$moduleLoader = new ModuleLoader($config);