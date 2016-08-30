<?php
return [
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'assessment/v1/rpc/frontend/frontend/frontend' => __DIR__ . '/../view/frontend/frontend.phtml',
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'router' => [
        'routes' => [
            'assessment.rest.users' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/user[/:user_id]',
                    'defaults' => [
                        'controller' => 'Assessment\\V1\\Rest\\Users\\Controller',
                    ],
                ],
            ],
            'assessment.rest.wallets' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/wallet[/:wallet_id]',
                    'defaults' => [
                        'controller' => 'Assessment\\V1\\Rest\\Wallets\\Controller',
                    ],
                ],
            ],
            'assessment.rest.bonuses' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/bonus[/:bonus_id]',
                    'defaults' => [
                        'controller' => 'Assessment\\V1\\Rest\\Bonuses\\Controller',
                    ],
                ],
            ],
            'assessment.rpc.auth' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/auth',
                    'defaults' => [
                        'controller' => 'Assessment\\V1\\Rpc\\Auth\\Controller',
                        'action' => 'auth',
                    ],
                ],
            ],
            'assessment.rpc.game' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/play',
                    'defaults' => [
                        'controller' => 'Assessment\\V1\\Rpc\\Game\\Controller',
                        'action' => 'game',
                    ],
                ],
            ],
            'assessment.rpc.deposit' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/deposit',
                    'defaults' => [
                        'controller' => 'Assessment\\V1\\Rpc\\Deposit\\Controller',
                        'action' => 'deposit',
                    ],
                ],
            ],
            'assessment.rpc.frontend' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/public',
                    'defaults' => [
                        'controller' => 'Assessment\\V1\\Rpc\\Frontend\\Controller',
                        'action' => 'frontend',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'assessment.rest.users',
            1 => 'assessment.rest.wallets',
            2 => 'assessment.rest.bonuses',
            3 => 'assessment.rpc.auth',
            5 => 'assessment.rpc.game',
            8 => 'assessment.rpc.deposit',
            9 => 'assessment.rpc.frontend',
        ],
    ],
    'zf-rest' => [
        'Assessment\\V1\\Rest\\Users\\Controller' => [
            'listener' => \Assessment\V1\Rest\Users\UsersResource::class,
            'route_name' => 'assessment.rest.users',
            'route_identifier_name' => 'user_id',
            'collection_name' => 'users',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'page',
                1 => 'sort',
                2 => 'order',
                3 => 'email',
                4 => 'name',
                5 => 'age',
                6 => 'gender',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Assessment\V1\Rest\Users\UsersEntity::class,
            'collection_class' => \Assessment\V1\Rest\Users\UsersCollection::class,
            'service_name' => 'users',
        ],
        'Assessment\\V1\\Rest\\Wallets\\Controller' => [
            'listener' => \Assessment\V1\Rest\Wallets\WalletsResource::class,
            'route_name' => 'assessment.rest.wallets',
            'route_identifier_name' => 'wallet_id',
            'collection_name' => 'wallets',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'page',
                1 => 'sort',
                2 => 'direction',
                3 => 'user_id',
                4 => 'bonus_id',
                5 => 'balance',
                6 => 'original',
                7 => 'currency',
                8 => 'active',
                9 => 'bonus',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Assessment\V1\Rest\Wallets\WalletsEntity::class,
            'collection_class' => \Assessment\V1\Rest\Wallets\WalletsCollection::class,
            'service_name' => 'wallets',
        ],
        'Assessment\\V1\\Rest\\Bonuses\\Controller' => [
            'listener' => \Assessment\V1\Rest\Bonuses\BonusesResource::class,
            'route_name' => 'assessment.rest.bonuses',
            'route_identifier_name' => 'bonus_id',
            'collection_name' => 'bonuses',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'page',
                1 => 'sort',
                2 => 'direction',
                3 => 'tigger',
                4 => 'value',
                5 => 'type',
                6 => 'multiplier',
                7 => 'active',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Assessment\V1\Rest\Bonuses\BonusesEntity::class,
            'collection_class' => \Assessment\V1\Rest\Bonuses\BonusesCollection::class,
            'service_name' => 'bonuses',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'Assessment\\V1\\Rest\\Users\\Controller' => 'HalJson',
            'Assessment\\V1\\Rest\\Wallets\\Controller' => 'HalJson',
            'Assessment\\V1\\Rest\\Bonuses\\Controller' => 'HalJson',
            'Assessment\\V1\\Rpc\\Auth\\Controller' => 'HalJson',
            'Assessment\\V1\\Rpc\\Game\\Controller' => 'HalJson',
            'Assessment\\V1\\Rpc\\Deposit\\Controller' => 'HalJson',
            'Assessment\\V1\\Rpc\\Frontend\\Controller' => 'Documentation',
        ],
        'accept_whitelist' => [
            'Assessment\\V1\\Rest\\Users\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Assessment\\V1\\Rest\\Wallets\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Assessment\\V1\\Rest\\Bonuses\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'Assessment\\V1\\Rpc\\Auth\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Assessment\\V1\\Rpc\\Game\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Assessment\\V1\\Rpc\\Deposit\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'Assessment\\V1\\Rpc\\Frontend\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'Assessment\\V1\\Rest\\Users\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
            ],
            'Assessment\\V1\\Rest\\Wallets\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
            ],
            'Assessment\\V1\\Rest\\Bonuses\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
            ],
            'Assessment\\V1\\Rpc\\Auth\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
            ],
            'Assessment\\V1\\Rpc\\Game\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
            ],
            'Assessment\\V1\\Rpc\\Deposit\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
            ],
            'Assessment\\V1\\Rpc\\Frontend\\Controller' => [
                0 => 'application/vnd.assessment.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \Assessment\V1\Rest\Users\UsersEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'assessment.rest.users',
                'route_identifier_name' => 'user_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Assessment\V1\Rest\Users\UsersCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'assessment.rest.users',
                'route_identifier_name' => 'user_id',
                'is_collection' => true,
            ],
            \Assessment\V1\Rest\Wallets\WalletsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'assessment.rest.wallets',
                'route_identifier_name' => 'wallet_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Assessment\V1\Rest\Wallets\WalletsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'assessment.rest.wallets',
                'route_identifier_name' => 'wallet_id',
                'is_collection' => true,
            ],
            \Assessment\V1\Rest\Bonuses\BonusesEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'assessment.rest.bonuses',
                'route_identifier_name' => 'bonus_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Assessment\V1\Rest\Bonuses\BonusesCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'assessment.rest.bonuses',
                'route_identifier_name' => 'bonus_id',
                'is_collection' => true,
            ],
            'Assessment\\V1\\Rest\\User\\UserEntity' => [
                'entity_identifier_name' => 'id',
                'route_name' => 'assessment.rest.users',
                'route_identifier_name' => 'user_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            'Assessment\\V1\\Rest\\User\\UserCollection' => [
                'entity_identifier_name' => 'id',
                'route_name' => 'assessment.rest.users',
                'route_identifier_name' => 'user_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-apigility' => [
        'db-connected' => [
            \Assessment\V1\Rest\Users\UsersResource::class => [
                'adapter_name' => 'Sqlite',
                'table_name' => 'users',
                'hydrator_name' => \Zend\Hydrator\ArraySerializable::class,
                'controller_service_name' => 'Assessment\\V1\\Rest\\Users\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'Assessment\\V1\\Rest\\Users\\UsersResource\\Table',
                'resource_class' => \Assessment\V1\Rest\Users\UsersResource::class,
            ],
            \Assessment\V1\Rest\Wallets\WalletsResource::class => [
                'adapter_name' => 'Sqlite',
                'table_name' => 'wallets',
                'hydrator_name' => \Zend\Hydrator\ArraySerializable::class,
                'controller_service_name' => 'Assessment\\V1\\Rest\\Wallets\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'Assessment\\V1\\Rest\\Wallets\\WalletsResource\\Table',
                'resource_class' => \Assessment\V1\Rest\Wallets\WalletsResource::class,
            ],
            \Assessment\V1\Rest\Bonuses\BonusesResource::class => [
                'adapter_name' => 'Sqlite',
                'table_name' => 'bonuses',
                'hydrator_name' => \Zend\Hydrator\ArraySerializable::class,
                'controller_service_name' => 'Assessment\\V1\\Rest\\Bonuses\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'Assessment\\V1\\Rest\\Bonuses\\BonusesResource\\Table',
                'resource_class' => \Assessment\V1\Rest\Bonuses\BonusesResource::class,
            ],
        ],
    ],
    'zf-content-validation' => [
        'Assessment\\V1\\Rest\\Users\\Controller' => [
            'input_filter' => 'Assessment\\V1\\Rest\\Users\\Validator',
        ],
        'Assessment\\V1\\Rest\\Wallets\\Controller' => [
            'input_filter' => 'Assessment\\V1\\Rest\\Wallets\\Validator',
        ],
        'Assessment\\V1\\Rest\\Bonuses\\Controller' => [
            'input_filter' => 'Assessment\\V1\\Rest\\Bonuses\\Validator',
        ],
        'Assessment\\V1\\Rpc\\Auth\\Controller' => [
            'input_filter' => 'Assessment\\V1\\Rpc\\Auth\\Validator',
        ],
        'Assessment\\V1\\Rpc\\Game\\Controller' => [
            'input_filter' => 'Assessment\\V1\\Rpc\\Game\\Validator',
        ],
        'Assessment\\V1\\Rpc\\Deposit\\Controller' => [
            'input_filter' => 'Assessment\\V1\\Rpc\\Deposit\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'Assessment\\V1\\Rest\\Users\\Validator' => [
            0 => [
                'name' => 'id',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            1 => [
                'name' => 'email',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            2 => [
                'required' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'password',
            ],
            3 => [
                'name' => 'name',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            4 => [
                'name' => 'age',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            5 => [
                'name' => 'gender',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
        ],
        'Assessment\\V1\\Rest\\Wallets\\Validator' => [
            0 => [
                'name' => 'id',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            1 => [
                'name' => 'user_id',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            2 => [
                'name' => 'bonus_id',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            3 => [
                'name' => 'balance',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            4 => [
                'name' => 'original',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            5 => [
                'name' => 'currency',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            6 => [
                'name' => 'active',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            7 => [
                'name' => 'bonus',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
        ],
        'Assessment\\V1\\Rest\\Bonuses\\Validator' => [
            0 => [
                'name' => 'id',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            1 => [
                'name' => 'trigger',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            2 => [
                'name' => 'value',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            3 => [
                'name' => 'type',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            4 => [
                'name' => 'multiplier',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            5 => [
                'name' => 'active',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
        ],
        'Assessment\\V1\\Rpc\\Auth\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\EmailAddress::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'email',
            ],
            1 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\StringLength::class,
                        'options' => [
                            'min' => '5',
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'password',
            ],
        ],
        'Assessment\\V1\\Rpc\\Game\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'user_id',
            ],
            1 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'bet',
            ],
        ],
        'Assessment\\V1\\Rpc\\Fund\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'user_id',
            ],
            1 => [
                'required' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'amount',
            ],
        ],
        'Assessment\\V1\\Rpc\\Deposit\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\GreaterThan::class,
                        'options' => [],
                    ],
                ],
                'filters' => [],
                'name' => 'user_id',
            ],
            1 => [
                'required' => true,
                'validators' => [
                    0 => [
                        'name' => \Zend\Validator\GreaterThan::class,
                        'options' => [
                            'min' => '0',
                        ],
                    ],
                ],
                'filters' => [],
                'name' => 'amount',
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'Assessment\\V1\\Rpc\\Auth\\Controller' => \Assessment\V1\Rpc\Auth\AuthControllerFactory::class,
            'Assessment\\V1\\Rpc\\Game\\Controller' => \Assessment\V1\Rpc\Game\GameControllerFactory::class,
            'Assessment\\V1\\Rpc\\Deposit\\Controller' => \Assessment\V1\Rpc\Deposit\DepositControllerFactory::class,
            'Assessment\\V1\\Rpc\\Frontend\\Controller' => \Assessment\V1\Rpc\Frontend\FrontendControllerFactory::class,
        ],
    ],
    'zf-rpc' => [
        'Assessment\\V1\\Rpc\\Auth\\Controller' => [
            'service_name' => 'Auth',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'assessment.rpc.auth',
        ],
        'Assessment\\V1\\Rpc\\Game\\Controller' => [
            'service_name' => 'Game',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'assessment.rpc.game',
        ],
        'Assessment\\V1\\Rpc\\Deposit\\Controller' => [
            'service_name' => 'Deposit',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'assessment.rpc.deposit',
        ],
        'Assessment\\V1\\Rpc\\Frontend\\Controller' => [
            'service_name' => 'Frontend',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'assessment.rpc.frontend',
        ],
    ],
];
