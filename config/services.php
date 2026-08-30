<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
    ],

    'groq' => [
        'key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto-Blog AI Provider Order
    |--------------------------------------------------------------------------
    |
    | ArticleGeneratorService tries these providers in order, falling back to
    | the next one on rate-limit (429) or any other failure. "strategy":
    | "fallback" always starts with the first entry; "round_robin" rotates
    | which provider goes first on each call (still falls back through the
    | rest) so load spreads evenly instead of always hammering the first one.
    |
    */
    'ai_providers' => [
        'order' => array_filter(array_map('trim', explode(',', env('AI_PROVIDER_ORDER', 'gemini,groq')))),
        'strategy' => env('AI_PROVIDER_STRATEGY', 'fallback'),
    ],

];
