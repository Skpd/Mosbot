<?php

namespace Runner;

return [
    'router' => [
        'routes' => [
            'home'        => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => 'Web\Controller\Index',
                        'action'     => 'index'
                    ]
                ]
            ],
            'players'     => [
                'type'          => 'literal',
                'may_terminate' => true,
                'options'       => [
                    'route'    => '/players',
                    'defaults' => [
                        'controller' => 'Web\Controller\Players',
                        'action'     => 'index'
                    ]
                ],
                'child_routes'  => [
                    'list' => [
                        'type'     => 'literal',
                        'options'  => [
                            'route' => '/list',
                            'defaults' => [
                                'action' => 'get-list'
                            ]
                        ],
                    ]
                ],
            ],
            'fight-stats' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/fight-stats[/:id][/]',
                    'defaults' => [
                        'controller' => 'Web\Controller\Index',
                        'action'     => 'fight-stats'
                    ]
                ]
            ],
            'library'     => [
                'type'          => 'literal',
                'may_terminate' => true,
                'options'       => [
                    'route'    => '/library',
                    'defaults' => [
                        'controller' => 'PhlySimplePage\Controller\Page',
                        'template'   => 'pages/library/home.phtml'
                    ]
                ],
                'child_routes'  => [
                    'max-stats' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/max-stats[/]',
                            'defaults' => [
                                'controller' => 'PhlySimplePage\Controller\Page',
                                'template'   => 'pages/library/max-stats.phtml'
                            ]
                        ]
                    ],
                ]
            ],
        ]
    ]
];