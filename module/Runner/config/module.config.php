<?php

namespace Runner;

return [
    'controllers' => [
        'invokables' => [
            'Runner\Controller\Index' => 'Runner\Controller\IndexController',
            'Runner\Controller\Proxy' => 'Runner\Controller\ProxyController',
        ],
    ],
];