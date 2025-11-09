<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => env('APP_NAME', 'Inmobiliaria API'),
            ],

            'routes' => [
                'api' => 'api/documentation',
            ],

            'paths' => [
                'docs' => 'storage/api-docs',
                'docs_json' => 'api-docs.json',
                'annotations' => [
                    base_path('app'),
                    base_path('routes'),
                ],
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            'middleware' => [
                'api',
            ],
        ],

        'paths' => [
            'use_absolute_path' => false,
            'docs_base_path' => '',
            'format_to_use_for_docs' => env('L5_SWAGGER_FORMAT', 'json'),
        ],
    ],
];
