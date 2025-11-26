<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
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

    'facebook' => [
        'app_id' => env('FACEBOOK_APP_ID'),
        'app_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
        'graph_api_version' => env('FACEBOOK_GRAPH_API_VERSION', 'v15.0'),
        'webhook_verify_token' => env('FACEBOOK_WEBHOOK_VERIFY_TOKEN'),
        'dialog_oauth' => env('FACEBOOK_DIALOG_OAUTH', 'https://www.facebook.com/v22.0/dialog/oauth'),
        'scopes' => [
            'pages_manage_metadata',
            'pages_messaging',
            'pages_read_engagement',
            'pages_show_list',
        ],
        'success_callback_redirect' => env('FACEBOOK_CALLBACK_SUCCESS_REDIRECT_URL', '/settings/chat'),
    ],

    'line' => [
        'webhook_verify_token' => env('LINE_WEBHOOK_VERIFY_TOKEN'),
    ],

];
