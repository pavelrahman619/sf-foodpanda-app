<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', '/auto-login'], // Added /auto-login for the token login route

    'allowed_methods' => ['*'],


    'allowed_origins' => [
        env('FOODPANDA_APP_URL', 'http://sf-foodpanda-app.test'), // Allow foodpanda-app
        env('ECOMMERCE_APP_URL', 'http://sf-ecommerce-app.test'), // Allow self for SPA-like behavior if any
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Required for Sanctum cookie-based auth & cross-domain requests

];
