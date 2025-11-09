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
                'docs' => 'docs',
                'oauth2_callback' => 'api/oauth2-callback',
            ],
            'paths' => [
                'docs' => storage_path('api-docs'),
                'docs_json' => 'api-docs.json',
                'docs_yaml' => 'api-docs.yaml',
                'annotations' => [
                    base_path('app/OpenApi'),
                    base_path('app/Http/Controllers'),
                ],
                'excludes' => [],
                'base' => env('L5_SWAGGER_BASE_PATH', ''),
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            'middleware' => [
                'api',
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
        ],

        'paths' => [
            'use_absolute_path' => false,
            'docs_base_path' => '',
            'format_to_use_for_docs' => env('L5_SWAGGER_FORMAT', 'json'),
        ],

        // Common l5-swagger defaults expected by middleware
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', true),
        'proxy' => env('L5_SWAGGER_PROXY', false),
        'additional_config_url' => env('L5_SWAGGER_ADDITIONAL_CONFIG_URL'),
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT'),
        'validator_url' => env('L5_SWAGGER_VALIDATOR_URL'),

        'securityDefinitions' => [
            'securitySchemes' => [],
        ],
        'ui' => [
            'display' => [
                'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),
                'filter' => env('L5_SWAGGER_UI_FILTER', false),
                'dark_mode' => env('L5_SWAGGER_UI_DARK_MODE', false),
            ],
            'authorization' => [
                'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),
                'oauth2' => [
                    'use_pkce_with_authorization_code_grant' => env('L5_SWAGGER_UI_OAUTH2_PKCE', false),
                ],
            ],
        ],
    ],
];
