let Encore = require('@symfony/webpack-encore');
const webpack = require("webpack");

Encore
    // the project directory where compiled assets will be stored

    .setOutputPath('public/build/')

    // the public path used by the web server to access the previous directory

    .setPublicPath('/build')

    .cleanupOutputBeforeBuild()

    .enableSourceMaps(!Encore.isProduction())


    // uncomment to define the assets of the project

    .addEntry('js/app', './assets/vue/main.js')

    .addStyleEntry('css/app', './assets/css/app.scss')

    .enableSingleRuntimeChunk()

    .enableSassLoader()

    // Enable Vue Loader
    .enableVueLoader(() => {}, {
        version: 3,
        runtimeCompilerBuild: true
    });

const config = Encore.getWebpackConfig();
config.plugins.push(
    new webpack.DefinePlugin({
        '__VUE_PROD_DEVTOOLS__': false,
        '__VUE_OPTIONS_API__': true
    })
)



module.exports = config