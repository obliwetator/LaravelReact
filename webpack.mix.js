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

mix.react('resources/js/app.js', 'public/js').sourceMaps()
mix.react('resources/js/upload.js', 'public/js').sourceMaps()
   //.sass('resources/sass/app.scss', 'public/css')


   mix.browserSync('http://127.0.0.1:8000/');
   if (mix.inProduction()) {
      mix.version();
   }
   mix.disableSuccessNotifications();
