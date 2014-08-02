<?php

$mongoConnectionString = 'mongodb://localhost:27017';
$mongoZoopDatabase = 'zoop_development';

return [
    'doctrine' => [
        'authentication' => [
            'adapter' => [
                'default' => [
                    'object_manager' => 'doctrine.odm.documentmanager.commerce',
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
    ],
    'zoop' => [
        'api' => [
            'endpoints' => [
                'users',
            ]
        ],
        'gateway' => [
            'document_manager' => 'doctrine.odm.documentmanager.commerce',
            'shard_manifest' => 'commerce',
            'authentication_service_options' => [
                'enable_per_request' => true,
                'enable_per_session' => false,
                'enable_remember_me' => false,
                'per_request_adapter' => 'Zoop\User\HttpAdapter',
            ],
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
            ],
            'rest' => [
                'rest' => [
                    'users' => [
                        'manifest' => 'commerce',
                        'class' => 'Zoop\User\DataModel\AbstractUser',
                        'property' => 'username',
                        'listeners' => [
                            'create' => [
                                'zoop.shardmodule.listener.unserialize',
                                'zoop.shardmodule.listener.create',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.location',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'delete' => [
                                'zoop.shardmodule.listener.delete',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
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
                            'patch' => [
                                'zoop.shardmodule.listener.unserialize',
                                'zoop.shardmodule.listener.idchange',
                                'zoop.shardmodule.listener.patch',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'patchList' => [],
                            'update' => [
                                'zoop.shardmodule.listener.unserialize',
                                'zoop.shardmodule.listener.idchange',
                                'zoop.shardmodule.listener.update',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'replaceList' => [],
                            'options' => [
                                'zoop.shardmodule.listener.options',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
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
