<?php

return array(
    'zoop' => [
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
                        'extension.crypt' => true
                    ],
                    'models' => [
                        'Zoop\User\DataModel' => __DIR__ . '/../src/Zoop/User/DataModel'
                    ],
                    'service_manager_config' => [
                        'factories' => [
                            'zoop.user.password.salt' => 'Zoop\User\Service\PasswordSaltFactory',
//                            'user' => ''
                        ]
                    ]
                ]
            ]
        ],
    ],
    'controllers' => [
        'invokables' => [
          
        ],
    ],
    'service_manager' => [
        'invokables' => [

        ],
        'factories' => [
            'Zend\Authentication\AuthenticationService' => 'Zoop\User\Service\AuthenticationServiceFactory',
            'zoop.user.active' => 'Zoop\User\Service\ActiveUserFactory',
//            'user' => 'zoop.user.active'
        ],
    ],
);
