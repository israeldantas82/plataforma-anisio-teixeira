let mix = require("laravel-mix");

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

//mix.extract(["vue", "lodash"]);

mix.browserSync({
  proxy: "http://pat.des"
});

mix.config.webpackConfig.output = {
  chunkFilename: "js/[name].bundle.js",
  publicPath: "/"
};

mix
  .js("resources/assets/js/app.js", "public/js")
  .stylus("resources/assets/stylus/app.styl", "public/css")
  .version();
