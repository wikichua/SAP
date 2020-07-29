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
        'resources/js/sb-admin-2.js'
    ], 'public/js/all.js')
    .scripts([
        'resources/js/datatable.js'
    ], 'public/js/datatable.min.js')
    .styles([
        'resources/sass/sb-admin-2.css'
    ], 'public/css/all.css')
    .sass(
        "resources/sass/app.scss",
        "public/css"
    );