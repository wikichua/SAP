const mix = require("laravel-mix");

mix.js("resources/js/app.js", "public/js")
    .scripts([
        'resources/js/jCookie.js',
        'resources/js/sb-admin-2.js',
        'resources/js/jquery.easing.js',
        'resources/js/serviceWorker.min.js'
    ], 'public/js/all.js')
    .scripts([
        'resources/js/datatableformhandling.js'
    ], 'public/js/datatableformhandling.min.js')
    .styles([
        'resources/sass/sb-admin-2.css'
    ], 'public/css/all.css')
    .sass(
        "resources/sass/app.scss",
        "public/css"
    ).extract([
        'jquery',
        'axios',
        'lodash',
        'moment',
        'popper.js',
    ])
    .extract([
        'bootstrap',
        'bootstrap-daterangepicker',
        'bootstrap-select',
        'bootstrap-table',
        'daterangepicker',
        'gijgo',
        'sweetalert2'
    ],'public/bootstrap/bootstrap-lib')
    .extract([
        'codemirror',
        'simplemde',
        'summernote'
    ],'public/bootstrap/editor-lib');
