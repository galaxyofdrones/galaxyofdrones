const mix = require('laravel-mix');
const webpack = require('webpack');

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

mix.options({
    processCssUrls: false
})
    .webpackConfig({
        plugins: [
            new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/)
        ]
    })
    .js('resources/assets/js/admin.js', 'public/js')
    .js('resources/assets/js/site.js', 'public/js')
    .sass('resources/assets/sass/admin.scss', 'public/css')
    .sass('resources/assets/sass/site.scss', 'public/css')
    .version();
