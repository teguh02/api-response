<?php

// config for teguh02/ApiResponse
return [

    /*
    |--------------------------------------------------------------------------
    | Package API Configuration
    |--------------------------------------------------------------------------
    |
    | This value is the default configuration for the API response.
    |
    | debug is used to enable or disable the debug mode.
    | display_meta is used to display the meta in the response.
    | response is used to set the response format.
    | pagination is used to set the pagination format.
    |
    */

    'api' => [
        'debug' => env('API_DEBUG', false),
        'display_meta' => env('API_DISPLAY_META', true),
        'display_status_code' => env('API_DISPLAY_STATUS_CODE', true),

        'response' => [
            'data_key' => env('API_RESPONSE_DATA_KEY', 'data'),
            'status_code_key' => env('API_RESPONSE_STATUS_CODE_KEY', 'status_code'),
        ],

        'pagination' => [
            'enabled' => env('API_PAGINATION_ENABLED', true),
            'per_page' => env('API_PAGINATION_PER_PAGE', 15),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default API Headers
    |--------------------------------------------------------------------------
    |
    | This value is the default headers that will be returned in the API.
    | You can override this value in the response.
    |
    */

    'headers' => [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default API Meta
    |--------------------------------------------------------------------------
    |
    | This value is the default meta that will be returned in the API.
    | You can override this value in the response.
    |
    */

    'meta' => [
        'version' => '1.0.0',
        'server_time' => now()->toDateTimeString(),
    ],
];
