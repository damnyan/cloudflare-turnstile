<?php

return [
    'turnstile' => [
        'base_uri' => env(
            key: 'CLOUDFLARE_TURNSTILE_BASE_URI',
            default: 'https://challenges.cloudflare.com/turnstile/v0'
        ),
        'secret' => env(key: 'CLOUDFLARE_TURNSTILE_SECRET'),
        'token_header_key' => env(
            key: 'CLOUDFLARE_TURNSTILE_TOKEN_HEADER_KEY',
            default: 'cf-t-token'
        ),
    ]
];
