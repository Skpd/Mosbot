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
            'Web\Controller\Players' => 'Web\Controller\PlayersController',
        ],
    ],
    'translator'      => array(
        'locale'                    => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
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