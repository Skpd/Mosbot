<?php

namespace Web;

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
            'FightAnalyzer' => function ($sm) {
                $service = new Service\FightAnalyzer;
                $service->setServiceLocator($sm);

                $service->getEventManager()->attach('analyze.action', new Fight\Events\BeginTime());
                $service->getEventManager()->attach('analyze.action', new Fight\Events\Separator());
                $service->getEventManager()->attach('analyze.action', new Fight\Events\Kill());
                $service->getEventManager()->attach('analyze.action', new Fight\Events\Heal());
                $service->getEventManager()->attach('analyze.action', new Fight\Events\Bang());
                $service->getEventManager()->attach('analyze.action', new Fight\Events\HelmetHit());
                $service->getEventManager()->attach('analyze.action', new Fight\Events\NormalHit());
                $service->getEventManager()->attach('analyze.action', new Fight\Events\StrikeHit());
                $service->getEventManager()->attach('analyze.action', new Fight\Events\PetHit());
                $service->getEventManager()->attach('analyze.action', new Fight\Events\Miss());

                $service->getEventManager()->attach('analyze.pre', new Fight\Events\Teams());

                $service->getEventManager()->attach('analyze.post', new Fight\Events\Winner());
                $service->getEventManager()->attach('analyze.post', new Fight\Events\DamagePercentage());

                return $service;
            }

        ),
        'invokables' => [
        ]
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