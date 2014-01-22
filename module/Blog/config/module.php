<?php

namespace Blog;

use Zend\ServiceManager\ServiceManager;

return [
    'controllers' => [
        'invokables' => [
            'Blog\Controller\View' => 'Blog\Controller\ViewController',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'blog_odm' => function ($serviceManager) {
                /** @var ServiceManager $serviceManager */
                return $serviceManager->get('doctrine.documentmanager.odm_default');
            },
        ],
    ],
    'view_manager'    => [
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
        'strategies'               => [
            'ViewJsonStrategy',
        ],
    ],
];