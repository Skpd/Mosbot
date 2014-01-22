<?php

namespace Blog;

return [
    'doctrine' => [
        'driver' => [
            'odm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__,
                ]
            ],
            __NAMESPACE__ => [
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\XmlDriver',
                'cache' => 'apc',
                'paths' => [
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                    __DIR__ . '/mapping',
                ]
            ],
        ],
    ],
];