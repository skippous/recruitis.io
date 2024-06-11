// webpack.config.js
const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel(null, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    // Allow Vue.js single file components
    .enableVueLoader()
    .enableSassLoader()
    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();
