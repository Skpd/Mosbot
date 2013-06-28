<?php

namespace Runner;

return [
    'controllers' => [
        'invokables' => [
            'Runner\Controller\Index' => 'Runner\Controller\IndexController',
            'Runner\Controller\Proxy' => 'Runner\Controller\ProxyController',
            'Runner\Controller\Spider' => 'Runner\Controller\SpiderController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'spider' => 'Runner\Service\Spider'
        ]
    ]
];