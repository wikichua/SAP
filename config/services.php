<?php
$custom_pub_path = config('sap.custom_pub_path')!=''? "/{config('sap.custom_pub_path')}/":'';
$redirectUrl = env('APP_URL').$custom_pub_path.'login/{%provider%}/callback';
return [
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => str_replace('{%provider%}', 'github', $redirectUrl),
        'scopes' => ['read:user', 'public_repo'],
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => str_replace('{%provider%}', 'facebook', $redirectUrl),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => str_replace('{%provider%}', 'google', $redirectUrl),
    ],
    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => str_replace('{%provider%}', 'linkedin', $redirectUrl),
    ],
    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => str_replace('{%provider%}', 'twitter', $redirectUrl),
    ],
];
