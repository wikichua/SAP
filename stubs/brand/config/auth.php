<?php

return [

    'defaults' => [
        'guard' => 'brand_web',
        'passwords' => 'users',
    ],

    'guards' => [
        'brand_web' => [
            'driver' => 'session',
            'provider' => 'brand_users',
        ],
    ],

    'providers' => [
        'brand_users' => [
            'driver' => 'eloquent',
            'model' => \Brand\{%brand_name%}\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'brand_users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
