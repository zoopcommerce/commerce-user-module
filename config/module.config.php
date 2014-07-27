<?php

$mongoConnectionString = 'mongodb://localhost:27017';
$mongoZoopDatabase = 'zoop_development';

return [
    'doctrine' => [
        'authentication' => [
            'adapter' => [
                'default' => [
                    'object_manager' => 'doctrine.odm.documentmanager.userauth',
                    'identity_class' => 'Zoop\User\DataModel\AbstractUser',
                    'identity_property' => 'username',
                    'credential_property' => 'password'
                ]
            ],
            'storage' => [
                'commerce' => [
                    'identity_class' => 'Zoop\User\DataModel\AbstractUser',
                ]
            ],
        ],
        'odm' => [
            'connection' => [
                'userauth' => [
                    'dbname' => $mongoZoopDatabase,
                    'connectionString' => $mongoConnectionString,
                ],
            ],
            'configuration' => [
                'userauth' => [
                    'class_metadata_factory_name' => 'Zoop\Shard\ODMCore\ClassMetadataFactory',
                    'metadata_cache' => 'doctrine.cache.juggernaut.filesystem',
                    'generate_proxies' => false,
                    'proxy_dir' => __DIR__ . '/../data/proxies',
                    'proxy_namespace' => 'proxies',
                    'generate_hydrators' => false,
                    'hydrator_dir' => __DIR__ . '/../data/hydrators',
                    'hydrator_namespace' => 'hydrators',
                    'default_db' => $mongoZoopDatabase,
                    'driver' => 'doctrine.driver.default',
                ]
            ],
            'documentmanager' => [
                'userauth' => [
                    'connection' => 'doctrine.odm.connection.userauth',
                    'configuration' => 'doctrine.odm.configuration.userauth',
                    'eventmanager' => 'doctrine.eventmanager.userauth'
                ]
            ],
            'eventmanager' => [
                'userauth' => [],
            ],
        ],
    ],
    'zoop' => [
        'api' => [
            'endpoints' => [
                'users',
            ]
        ],
        'gateway' => [
            'document_manager' => 'doctrine.odm.documentmanager.userauth',
            'shard_manifest' => 'userauth',
            'authentication_service_options' => [
                'enable_per_request' => true,
                'enable_per_session' => false,
                'enable_remember_me' => false,
                'per_request_adapter' => 'Zoop\User\HttpAdapter',
            ]
        ],
        'user' => [
            'crypt' => [
                'salt' => [  //this is a development salt only
                    'password' => '349058BJgi789yjklhuksbkhw4576sw7',
                ],
                'key' => [ //this is a development key only
                ]
            ],
        ],
        'shard' => [
            'manifest' => [
                'commerce' => [
                    'extension_configs' => [
                        'extension.accesscontrol' => true,
                        'extension.crypt' => true,
                        'extension.serializer' => true,
                        'extension.validator' => true,
                        'extension.stamp' => true,
                    ],
                    'models' => [
                        'Zoop\User\DataModel' => __DIR__ . '/../src/Zoop/User/DataModel'
                    ],
                    'service_manager_config' => [
                        'abstract_factories' => [
                            'Zoop\ShardModule\Service\UserAbstractFactory'
                        ],
                        'factories' => [
                            'crypt.emailaddress' => 'Zoop\GomiModule\Service\CryptEmailAddressFactory',
                        ]
                    ]
                ],
                'userauth' => [
                    'model_manager' => 'doctrine.odm.documentmanager.userauth',
                    'extension_configs' => [
                        'extension.odmcore' => true,
                        'extension.softDelete' => true,
                        'extension.accesscontrol' => false,
                        'extension.crypt' => true,
                        'extension.serializer' => true,
                        'extension.validator' => true,
                        'extension.stamp' => true,
                        'extension.state' => true,
                        'extension.zone' => true
                    ],
                    'models' => [
                        'Zoop\User\DataModel' => __DIR__ . '/../src/Zoop/User/DataModel'
                    ],
                    'service_manager_config' => [
                        'abstract_factories' => [
                            'Zoop\ShardModule\Service\UserAbstractFactory'
                        ],
                        'factories' => [
                            'crypt.emailaddress' => 'Zoop\GomiModule\Service\CryptEmailAddressFactory',
                            'modelmanager' => 'Zoop\Common\Database\Service\UserAuthDocumentManagerFactory',
                            'eventmanager' => 'Zoop\ShardModule\Service\EventManagerFactory'
                        ]
                    ]
                ]
            ],
            'rest' => [
                'rest' => [
                    'users' => [
                        'manifest' => 'commerce',
                        'class' => 'Zoop\User\DataModel\AbstractUser',
                        'property' => 'username',
                        'listeners' => [
                            'create' => [],
                            'delete' => [],
                            'deleteList' => [],
                            'get' => [
                                'zoop.shardmodule.listener.get',
                                'zoop.shardmodule.listener.serialize',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'getList' => [
                                'zoop.shardmodule.listener.getlist',
                                'zoop.shardmodule.listener.serialize',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'options' => [],
                            'patch' => [],
                            'patchList' => [],
                            'update' => [],
                            'replaceList' => [],
                        ],
                    ],
                ]
            ]
        ],
    ],
    'controllers' => [
        'invokables' => [
        ],
    ],
    'service_manager' => [
        'factories' => [
            'Zoop\User\HttpAdapter' => 'Zoop\User\Service\HttpAdapterServiceFactory',
        ],
    ],
];
