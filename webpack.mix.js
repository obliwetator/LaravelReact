const mix = require("laravel-mix");

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

mix.sass('resources/sass/app.scss', 'public/css');

// mix.react('resources/js/app.js', 'public/js').sourceMaps()

mix
  .react("resources/js/app.js", "public/js")
  .sourceMaps()
  .webpackConfig({
    module: {
      rules: [
        {
          test: /\.tsx?$/,
          loader: "ts-loader",
          exclude: /node_modules/
        }
      ]
    },
    resolve: {
      extensions: ["*", ".js", ".jsx", ".vue", ".ts", ".tsx"]
    }
  });
// mix.react('resources/js/upload.js', 'public/js').sourceMaps()
mix
  .react("resources/js/upload.js", "public/js")
  .sourceMaps()
  .webpackConfig({
    module: {
      rules: [
        {
          test: /\.tsx?$/,
          loader: "ts-loader",
          exclude: /node_modules/
        }
      ]
    },
    resolve: {
      extensions: ["*", ".js", ".jsx", ".vue", ".ts", ".tsx"]
    }
  });

mix.browserSync({ 
  proxy: 'http://127.0.0.1:8000/',
  ignore: {
    ignoreInitial: true,
    ignored: '*.css'
},
});

if (mix.inProduction()) {
  mix.version();
}
mix.disableSuccessNotifications();
