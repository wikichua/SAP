<?php
$redirectUrl = secure_url('/auth/login/{%provider%}/callback');
return [
    'github' => [
        'client_id' => env('{%brand_capital%}_GITHUB_CLIENT_ID'),
        'client_secret' => env('{%brand_capital%}_GITHUB_CLIENT_SECRET'),
        'redirect' => str_replace('{%provider%}', 'github', $redirectUrl),
    ],
    'facebook' => [
        'client_id' => env('{%brand_capital%}_FACEBOOK_CLIENT_ID'),
        'client_secret' => env('{%brand_capital%}_FACEBOOK_CLIENT_SECRET'),
        'redirect' => str_replace('{%provider%}', 'facebook', $redirectUrl),
    ],
    'google' => [
        'client_id' => env('{%brand_capital%}_GOOGLE_CLIENT_ID'),
        'client_secret' => env('{%brand_capital%}_GOOGLE_CLIENT_SECRET'),
        'redirect' => str_replace('{%provider%}', 'google', $redirectUrl),
    ],
    'linkedin' => [
        'client_id' => env('{%brand_capital%}_LINKEDIN_CLIENT_ID'),
        'client_secret' => env('{%brand_capital%}_LINKEDIN_CLIENT_SECRET'),
        'redirect' => str_replace('{%provider%}', 'linkedin', $redirectUrl),
    ],
    'twitter' => [
        'client_id' => env('{%brand_capital%}_TWITTER_CLIENT_ID'),
        'client_secret' => env('{%brand_capital%}_TWITTER_CLIENT_SECRET'),
        'redirect' => str_replace('{%provider%}', 'twitter', $redirectUrl),
    ],
];
