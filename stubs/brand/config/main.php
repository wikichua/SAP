<?php

return [
    'resources_path' => base_path('brand/{%brand_name%}/resources/views'),
    'template_path' => base_path('brand/{%brand_name%}/resources/views/layouts'),
    'admin_path' => base_path('brand/{%brand_name%}/resources/views/admin'),

    'models' => [
        'user' => 'Brand\{%brand_name%}\Models\User',
    ],
];
