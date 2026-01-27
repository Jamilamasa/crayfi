<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cray Finance API Key
    |--------------------------------------------------------------------------
    |
    | This key is used to authenticate requests to the Cray Finance API.
    | You can find your API key in your Cray Finance dashboard.
    |
    */
    'api_key' => env('CRAY_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Cray Finance Environment
    |--------------------------------------------------------------------------
    |
    | The environment to use for API requests. Can be 'sandbox' or 'live'.
    | This determines the base URL used for requests.
    |
    */
    'env' => env('CRAY_ENV', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Cray Finance Base URL
    |--------------------------------------------------------------------------
    |
    | You can explicitly set the base URL here, or leave it null to use
    | the default URL for the configured environment.
    |
    */
    'base_url' => env('CRAY_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The number of seconds to wait before timing out a request.
    |
    */
    'timeout' => env('CRAY_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Request Retries
    |--------------------------------------------------------------------------
    |
    | The number of times to retry a failed request.
    |
    */
    'retries' => env('CRAY_RETRIES', 2),
];
