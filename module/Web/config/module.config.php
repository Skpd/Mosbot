<?php

namespace Web;

use Web\Fight\Events;

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

                $eventManager = $service->getEventManager();

                $eventManager->attach('analyze.action', new Events\BeginTime());
                $eventManager->attach('analyze.action', new Events\Separator());
                $eventManager->attach('analyze.action', new Events\Kill());
                $eventManager->attach('analyze.action', new Events\Heal());
                $eventManager->attach('analyze.action', new Events\ForceJoin());
                $eventManager->attach('analyze.action', new Events\Bang());
                $eventManager->attach('analyze.action', new Events\HelmetHit());
                $eventManager->attach('analyze.action', new Events\Shield());
                $eventManager->attach('analyze.action', new Events\Hit());
                $eventManager->attach('analyze.action', new Events\Miss());
                $eventManager->attach('analyze.action', new Events\Reflect());
                $eventManager->attach('analyze.action', new Events\Cheese());
                $eventManager->attach('analyze.action', new Events\SovetAbilities());
                $eventManager->attach('analyze.action', new Events\Banish());
                $eventManager->attach('analyze.action', new Events\Flag());
                $eventManager->attach('analyze.action', new Events\Shout());

                $eventManager->attach('analyze.pre', new Events\Teams());

                $eventManager->attach('analyze.post', new Events\Winner());
                $eventManager->attach('analyze.post', new Events\DamagePercentage());
                $eventManager->attach('analyze.post', new Events\Finished());

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