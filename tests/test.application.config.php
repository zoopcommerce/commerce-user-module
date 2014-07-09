<?php

return [
    'modules' => [
        'Zoop\MaggottModule',
        'DoctrineModule',
        'DoctrineMongoODMModule',
        'Zoop\ShardModule',
        'Zoop\Api',
        'Zoop\Common',
        'Zoop\User',
        'Zoop\Store'
    ],
    'module_listener_options' => [
        'config_glob_paths' => [
            __DIR__ . '/../config/module.config.php',
            __DIR__ . '/test.module.config.php',
        ],
    ],
];
