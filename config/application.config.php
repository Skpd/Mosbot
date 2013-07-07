<?php

return [
    'modules'                 => [
        'DoctrineModule',
        'DoctrineMongoODMModule',
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