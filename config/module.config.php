<?php

return array(
    'zoop' => [
        'api' => [
            'endpoints' => [
                'users',
            ]
        ],
        'gateway' => [
            'document_manager' => 'doctrine.odm.documentmanager.commerce',
            'shard_manifest'   => 'commerce',
            'authentication_service_options' => [
                'enable_per_request'    => true,
                'enable_per_session'    => false,
                'enable_remember_me'    => false,
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
                            'zoop.user.password.salt' => 'Zoop\User\Service\PasswordSaltFactory',
                            'crypt.emailaddress' => 'Zoop\GomiModule\Service\CryptEmailAddressFactory',
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
//            'Zend\Authentication\AuthenticationService' => 'Zoop\User\Service\AuthenticationServiceFactory',
//            'zoop.user.authentication.adapter.http' => 'Zoop\User\Service\HttpBasicResolverFactory',
//            'zoop.user.active' => 'Zoop\User\Service\ActiveUserFactory',
        ],
    ],
);
