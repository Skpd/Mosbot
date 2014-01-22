<?php

namespace Blog;

return [
    'router' => [
        'routes' => [
            'blog' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/blog',
                    'defaults' => [
                        'controller' => 'Blog\Controller\View',
                        'action' => 'index'
                    ]
                ],
                'child_routes' => [
                    'list' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/list',
                            'defaults' => [
                                'action' => 'list'
                            ]
                        ],
                    ]
                ],
            ],
        ],
    ],

];