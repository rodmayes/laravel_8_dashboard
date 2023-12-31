const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.sass', 'public/css')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('resources/css/ijaboCropTool.min.css', 'public/css')
    .copy('resources/js/ijaboCropTool.min.js', 'public/js')
    //.copy('node_modules/admin-lte/dist/css/adminlte.css', 'public/css')
    //.copy('node_modules/admin-lte/dist/js/adminlte.js', 'public/js')
    //.copy('node_modules/@fortawesome/fontawesome-free/css/all.min.css', 'public/css')
    //.copy('node_modules/select2/dist/css/select2.min.css', 'public/css')
    //.copy('node_modules/select2-bootstrap-theme/dist/select2-bootstrap.min.css', 'public/css')
    //.copy('node_modules/bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.min.css', 'public/css')
    //.copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/webfonts')
    .sourceMaps();

mix.postCss('resources/css/main.css', 'public/css', [
    require('tailwindcss'),
])

mix.webpackConfig({
    stats: {
        children: true,
    },
});
