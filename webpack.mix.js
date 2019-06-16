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

mix.options({ processCssUrls: false })
    .webpackConfig({
        plugins: [
            new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/)
        ]
    })
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copy('resources/images/favicon.ico', 'public/favicon.ico')
    .copy('resources/images/logo.png', 'public/images/logo.png')
    .copy('resources/images/singleplayer.png', 'public/images/singleplayer.png')
    .copy('resources/images/sprite-grid.png', 'public/images/sprite-grid.png')
    .copy('resources/images/donation-1.png', 'public/images/donation-1.png')
    .copy('resources/images/donation-2.png', 'public/images/donation-2.png')
    .copy('resources/images/donation-3.png', 'public/images/donation-3.png')
    .copy('resources/images/donation-4.png', 'public/images/donation-4.png')
    .version()
    .extract();
