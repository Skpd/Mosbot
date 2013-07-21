<?php

namespace Runner;

return [
    'form_elements' => [
        'invokables' => [
            'FightStatsForm' => 'Web\Form\FightStats'
        ]
    ],
    'controllers' => [
        'invokables' => [
            'Web\Controller\Index' => 'Web\Controller\IndexController',
        ],
    ],
    'view_manager'    => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/500',
        'template_map'             => [
            'layout/layout'      => __DIR__ . '/../view/generic/layout-default.phtml',
        ],
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
        'strategies'               => [
            'ViewJsonStrategy',
        ],
    ],
];