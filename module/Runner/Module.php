<?php

namespace Runner;

use Zend\Config\Config;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ConsoleUsageProviderInterface
{
    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            'login <username> <password>',
            'proxy gather',
            'proxy check',
            'spider catch players [--id=]'
        ];
    }


    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            AutoloaderFactory::STANDARD_AUTOLOADER => [
                StandardAutoloader::LOAD_NS => array(
                    __NAMESPACE__ => __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . __NAMESPACE__,
                ),
            ]
        ];
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        $config = new Config(array());

        $iterator  = new \RegexIterator(new \DirectoryIterator(__DIR__ . '/config'), '#\.config\.php$#i');
        foreach ($iterator as $file) {
            /** @var $file \DirectoryIterator */
            if ($file->isReadable()) {
                $subConf = new Config(include $file->getRealPath());
                $config->merge($subConf);
            }
        }

        return $config;
    }
}