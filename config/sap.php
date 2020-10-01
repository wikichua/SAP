<?php

return [
    /* customization */
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
    'custom_facade_namespace' => 'App\Facades',
    'custom_facade_dir' => 'Facades',
    /* end customization */

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
    'sub_route_dir' => 'routes/routers',
    'sub_api_route_dir' => 'routes/routers/api',
    'models' => [
        'user' => config('auth.providers.users.model', '\App\User'),
        'role' => '\Wikichua\SAP\Models\Role',
        'permission' => '\Wikichua\SAP\Models\Permission',
        'setting' => '\Wikichua\SAP\Models\Setting',
        'report' => '\Wikichua\SAP\Models\Report',
        'activity_log' => '\Wikichua\SAP\Models\ActivityLog',
        'brand' => '\Wikichua\SAP\Models\Brand',
        'page' => '\Wikichua\SAP\Models\Page',
        'nav' => '\Wikichua\SAP\Models\Nav',
        'component' => '\Wikichua\SAP\Models\Component',
    ],
    'stub_path' => 'vendor/wikichua/sap/stubs',
    'elasticsearch' => [
        'enabled' => env('ELASTICSEARCH_ENABLED', false),
        'hosts' => explode(',', env('ELASTICSEARCH_HOSTS', "localhost:9200")),
    ],
];
