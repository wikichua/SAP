<?php
return [
    'resources_path' => base_path('brand/{%brand_string%}/resources/views'),
    'template_path' => base_path('brand/{%brand_string%}/resources/views/layouts'),

    'models' => [
        'user' => 'Brand\{%brand_name%}\Models\User'
    ],
];
