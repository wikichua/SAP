<?php

return [
    /* customization */
    'custom_controller_namespace' => 'App\Http\Controllers\Admin',
    'custom_controller_dir' => 'Http/Controllers/Admin',
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
    'models' => [
        'user' => '\App\User',
        'role' => '\Wikichua\SAP\Models\Role',
        'permission' => '\Wikichua\SAP\Models\Permission',
        'setting' => '\Wikichua\SAP\Models\Setting',
        'activity_log' => '\Wikichua\SAP\Models\ActivityLog',
    ],
    // 'components' => [ // auto prepended with sap-
    //     'menu' => \Wikichua\SAP\View\Components\Menu::class,
    //     'datatable' => \Wikichua\SAP\View\Components\Datatable::class,
    //     'input-field' => \Wikichua\SAP\View\Components\InputField::class,
    // ],
    'stub_path' => 'vendor/wikichua/sap/stubs',
];
