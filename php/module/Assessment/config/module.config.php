<?php
return [
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
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'assessment.rest.users',
            1 => 'assessment.rest.wallets',
            2 => 'assessment.rest.bonuses',
        ],
    ],
    'zf-rest' => [
        'Assessment\\V1\\Rest\\Users\\Controller' => [
            'listener' => 'Assessment\\V1\\Rest\\Users\\UsersResource',
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
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Assessment\V1\Rest\Users\UsersEntity::class,
            'collection_class' => \Assessment\V1\Rest\Users\UsersCollection::class,
            'service_name' => 'users',
        ],
        'Assessment\\V1\\Rest\\Wallets\\Controller' => [
            'listener' => 'Assessment\\V1\\Rest\\Wallets\\WalletsResource',
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
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Assessment\V1\Rest\Wallets\WalletsEntity::class,
            'collection_class' => \Assessment\V1\Rest\Wallets\WalletsCollection::class,
            'service_name' => 'wallets',
        ],
        'Assessment\\V1\\Rest\\Bonuses\\Controller' => [
            'listener' => 'Assessment\\V1\\Rest\\Bonuses\\BonusesResource',
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
            'collection_query_whitelist' => [],
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
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \Assessment\V1\Rest\Users\UsersEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'assessment.rest.users',
                'route_identifier_name' => 'users_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \Assessment\V1\Rest\Users\UsersCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'assessment.rest.users',
                'route_identifier_name' => 'users_id',
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
            'Assessment\\V1\\Rest\\Users\\UsersResource' => [
                'adapter_name' => 'Sqlite',
                'table_name' => 'users',
                'hydrator_name' => \Zend\Hydrator\ArraySerializable::class,
                'controller_service_name' => 'Assessment\\V1\\Rest\\Users\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'Assessment\\V1\\Rest\\Users\\UsersResource\\Table',
            ],
            'Assessment\\V1\\Rest\\Wallets\\WalletsResource' => [
                'adapter_name' => 'Sqlite',
                'table_name' => 'wallets',
                'hydrator_name' => \Zend\Hydrator\ArraySerializable::class,
                'controller_service_name' => 'Assessment\\V1\\Rest\\Wallets\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'Assessment\\V1\\Rest\\Wallets\\WalletsResource\\Table',
            ],
            'Assessment\\V1\\Rest\\Bonuses\\BonusesResource' => [
                'adapter_name' => 'Sqlite',
                'table_name' => 'bonuses',
                'hydrator_name' => \Zend\Hydrator\ArraySerializable::class,
                'controller_service_name' => 'Assessment\\V1\\Rest\\Bonuses\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'Assessment\\V1\\Rest\\Bonuses\\BonusesResource\\Table',
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
                'name' => 'name',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            3 => [
                'name' => 'age',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            4 => [
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
    ],
];
