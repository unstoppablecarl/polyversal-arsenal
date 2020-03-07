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

// Prevents Mix's automatic configuring of jQuery, so that we
// can set it up ourselves later
// https://github.com/JeffreyWay/laravel-mix/issues/229#issuecomment-276230983
mix.autoload({});

mix
    .js('resources/js/app.js', 'public/build/js')
    // .sourceMaps()
    .extract(['vue', 'vuex', 'axios'])

    .sass('resources/sass/app.scss', 'public/build/css')
    .sass('resources/sass/tile-front-svg.scss', 'public/build/css')
    .sass('resources/sass/tile-back-svg.scss', 'public/build/css')
    // .copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/fonts')

    .options({
        processCssUrls: false,
    });

    mix.version();

mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.(ttf|eot|woff|woff2)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        name: '../fonts/[name].[ext]',
                    },
                },
            },
        ],
    },
});
