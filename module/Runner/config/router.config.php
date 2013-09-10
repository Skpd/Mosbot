<?php

namespace Runner;

return [
    'console' => [
        'router' => [
            'routes' => [
                'login' => [
                    'options' => [
                        'route'    => 'login <username> [<password>]',
                        'defaults' => [
                            'controller' => 'Runner\Controller\Index',
                            'action'     => 'index'
                        ]
                    ]
                ],
                'proxy-gather' => [
                    'options' => [
                        'route'    => 'proxy gather',
                        'defaults' => [
                            'controller' => 'Runner\Controller\Proxy',
                            'action'     => 'gather'
                        ]
                    ]
                ],
                'proxy-check' => [
                    'options' => [
                        'route'    => 'proxy check',
                        'defaults' => [
                            'controller' => 'Runner\Controller\Proxy',
                            'action'     => 'check'
                        ]
                    ]
                ],
                'spider-players' => [
                    'options' => [
                        'route'    => 'spider catch players [--id=]',
                        'defaults' => [
                            'controller' => 'Runner\Controller\Spider',
                            'action'     => 'get-players'
                        ]
                    ]
                ],
                'spider-players-level' => [
                    'options' => [
                        'route'    => 'spider update --level=',
                        'defaults' => [
                            'controller' => 'Runner\Controller\Spider',
                            'action'     => 'update-by-level'
                        ]
                    ]
                ],
            ],
        ],
    ],
];