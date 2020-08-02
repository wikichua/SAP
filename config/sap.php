<?php

return [
    /* customization */
    'custom_controller_namespace' => 'App\Http\Controllers\Admin',
    'custom_controller_dir' => 'Http/Controllers/Admin',
    'custom_view_dir' => 'admin',
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
    'model_namespace' => 'app',
    'controller_namespace' => '\Wikichua\SAP\Http\Controllers',
    'component_namespace' => '\Wikichua\SAP\View\Components',
    'routes_dir' => 'routes/web.php',
    'sub_route_dir' => 'routes/routers',
    'models' => [
        'user' => '\App\User',
        'role' => '\Wikichua\SAP\Models\Role',
        'permission' => '\Wikichua\SAP\Models\Permission',
        'setting' => '\Wikichua\SAP\Models\Setting',
        'activity_log' => '\Wikichua\SAP\Models\ActivityLog',
    ],
    'stub_path' => 'vendor/wikichua/sap/stubs',
];
