let mix = require('webpack-mix');
const tailwindcss = require('tailwindcss');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

// mix.js('assets/src/app.js', 'dist/').sass('src/app.scss', 'dist/');

mix.js('src/js/app.js', 'assets/js')
   .js('src/js/frontend.js','assets/js')
   .postCss('src/css/app.css', 'assets/css',
  	tailwindcss('./tailwind.config.js')
);