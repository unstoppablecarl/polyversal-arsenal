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
    .js('resources/js/app.js', 'public/js')
    .sourceMaps()
    .extract(['vue', 'vuex', 'axios'])

    .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
    });

if (mix.inProduction()) {
    mix.version();
}

mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.(ttf|eot|woff|woff2)$/,
                use : {
                    loader : "file-loader",
                    options: {
                        name: "../fonts/[name].[ext]",
                    },
                }
            }
        ]
    }
});
