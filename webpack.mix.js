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
    .vue({ version: 2 });
mix
    .sourceMaps()
    .extract(['vue', 'vuex', 'axios'])

    .sass('resources/sass/app.scss', 'public/build/css')
    .sass('resources/sass/tile-front-svg.scss', 'public/build/css')
    .sass('resources/sass/tile-back-svg.scss', 'public/build/css')

    .options({
        processCssUrls: false,
    });

    mix.version();

mix.webpackConfig({
    resolve: {
        alias: {
            // maps fs to a virtual one allowing to register file content dynamically
            fs: 'pdfkit/js/virtual-fs.js',
            // iconv-lite is used to load cid less fonts (not spec compliant)
            'iconv-lite': false
        },
        fallback: {
            // crypto module is not necessary at browser
            crypto: false,
            // fallbacks for native node libraries
            buffer: require.resolve('buffer/'),
            stream: require.resolve('readable-stream'),
            zlib: require.resolve('browserify-zlib'),
            util: require.resolve('util/'),
            assert: require.resolve('assert/')
        }
    },
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
            { test: /\.afm$/, type: 'asset/source' },
            // bundle and load binary files inside static-assets folder as base64
            {
                test: /src[/\\]static-assets/,
                type: 'asset/inline',
                generator: {
                    dataUrl: content => {
                        return content.toString('base64');
                    }
                }
            },
            // load binary files inside lazy-assets folder as an URL
            {
                test: /src[/\\]lazy-assets/,
                type: 'asset/resource'
            },
        ],
    },
});
