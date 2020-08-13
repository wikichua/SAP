const mix = require("laravel-mix");
const path = require("path");

mix.webpackConfig({
    resolve: {
        alias: {
            ziggy: path.resolve("vendor/tightenco/ziggy/src/js/route.js")
        }
    }
});

mix.js("resources/js/app.js", "public/js")
    .scripts([
        'resources/js/jCookie.js',
        'resources/js/sb-admin-2.js',
        'resources/js/jquery.easing.js',
        'resources/js/serviceWorker.min.js',
        'resources/js/push.min.js'
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
    );
