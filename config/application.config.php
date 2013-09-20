<?php

return [
    'modules'                 => [
        'DoctrineModule',
        'DoctrineMongoODMModule',
        'DoctrineORMModule',
        'ZfcAdmin',
        'Soflomo\Purifier',
        'Soflomo\Common',
        'Soflomo\Blog',
        'PhlySimplePage',
        'Runner',
        'Web',
    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            'config/autoload/{,*.}{global,local}.php',
        ],
        'module_paths'      => [
            './module',
            './vendor',
        ],
    ],
];