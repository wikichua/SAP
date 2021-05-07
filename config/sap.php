<?php

return [
    // customization
    'custom_model_namespace' => 'App\Models',
    'custom_model_dir' => 'Models',
    'custom_controller_namespace' => 'App\Http\Controllers\Admin',
    'custom_controller_dir' => 'Http/Controllers/Admin',
    'custom_api_controller_namespace' => 'App\Http\Controllers\Api',
    'custom_api_controller_dir' => 'Http/Controllers/Api',
    'custom_view_dir' => 'admin',
    'custom_admin_path' => 'admin',
    'custom_pub_path' => 'pub',
    'custom_service_namespace' => 'App\Services',
    'custom_service_dir' => 'Services',
    'custom_mail_namespace' => 'App\Mail',
    'custom_mail_dir' => 'Mail',
    'custom_facade_namespace' => 'App\Facades',
    'custom_facade_dir' => 'Facades',
    'custom_broadcast_driver' => 'ably', // pusher or ably or ''
    // end customization

    'hidden_auth_route_names' => [
        'password_email' => false,
        'password_request' => false,
        'password_reset' => false,
        'password_confirm' => false,
        'login' => false,
        'register' => false,
        'logout' => false,
    ],
    'model_namespace' => '\Wikichua\SAP\Models',
    'controller_namespace' => '\Wikichua\SAP\Http\Controllers',
    'component_namespace' => '\Wikichua\SAP\View\Components',
    'routes_dir' => 'routes/web.php',
    'models' => [
        'user' => config('auth.providers.users.model', '\App\User'),
        'versionizer' => '\Wikichua\SAP\Models\Versionizer',
        'searchable' => '\Wikichua\SAP\Models\Searchable',
        'role' => '\Wikichua\SAP\Models\Role',
        'permission' => '\Wikichua\SAP\Models\Permission',
        'setting' => '\Wikichua\SAP\Models\Setting',
        'report' => '\Wikichua\SAP\Models\Report',
        'activity_log' => '\Wikichua\SAP\Models\ActivityLog',
        'failed_job' => '\Wikichua\SAP\Models\FailedJob',
        'brand' => '\Wikichua\SAP\Models\Brand',
        'page' => '\Wikichua\SAP\Models\Page',
        'nav' => '\Wikichua\SAP\Models\Nav',
        'component' => '\Wikichua\SAP\Models\Component',
        'carousel' => '\Wikichua\SAP\Models\Carousel',
        'cronjob' => '\Wikichua\SAP\Models\Cronjob',
        'mailer' => '\Wikichua\SAP\Models\Mailer',
        'pusher' => '\Wikichua\SAP\Models\Pusher',
        'alert' => '\Wikichua\SAP\Models\Alert',
    ],
    'stub_path' => 'vendor/wikichua/sap/stubs',
    'activity_log' => [
        'masks' => [ // maskign the key in data field within the activity log model
            'password',
            'password_confirmation',
            'token',
        ],
    ],
    'reauth' => [
        'timeout' => 600, // default 10 mins
        'reset' => true,
    ],
];
