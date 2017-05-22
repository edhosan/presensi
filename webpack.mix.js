const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .options({
      processCssUrls: false
   });

mix.copyDirectory('node_modules/easy-autocomplete', 'public/easy-autocomplete');
mix.copyDirectory('node_modules/datatables.net', 'public/datatables.net');
mix.copyDirectory('node_modules/datatables.net-dt', 'public/datatables.net-dt');
mix.copyDirectory('node_modules/jquery/dist', 'public/jquery');
