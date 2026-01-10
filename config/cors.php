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

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // For development, allow all origins
    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        '*', // Allow all for development
        // 'https://yourdomain.com', // For production, specify exact origins
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // When allowed_origins is ['*'], supports_credentials must be false
    // Set to true only when using specific origins
    'supports_credentials' => false,

];
