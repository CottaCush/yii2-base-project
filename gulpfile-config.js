/* Import Node.js modules */
var environments = require('gulp-environments'),
    autoprefixer = require('autoprefixer');


var feConfig = {
    sourceDir: "./app/web",
    buildDir: "./app/web",
    styles: {
        sourceDir: "./app/web/less",
        sourceFiles: "./app/web/less/styles.less",
        destinationDir: "./app/web/css",
        mapsDir: "./maps", // relative to the destination directory
        postcss: [
            autoprefixer({browsers: ["last 5 versions", "> .5% in NG", "not ie < 11"]})
        ]
    },
    scripts: {
        shouldTranspile: true, // set to true/false to transpile higher javascript versions
        sourceDir: "./app/web/js",
        sourceFiles: ["./app/web/js/**/*.js"],
        destinationDir: "./app/web/js-dist"
    },
    images: {
        shouldMinify: false,
        sourceDir: "./app/web/img",
        sourceFiles: "./app/web/img/**/*",
        destinationDir: "./" // save minified images in the same directory
    }
};

var adminConfig = {
    sourceDir: "./app/web/admin-assets",
    buildDir: "./app/web/admin-assets",
    styles: {
        sourceDir: "./app/web/admin-assets/less",
        sourceFiles: "./app/web/admin-assets/less/styles.less",
        destinationDir: "./app/web/admin-assets/css",
        mapsDir: "./maps", // relative to the destination directory
        postcss: [
            autoprefixer({browsers: ["last 5 versions", "> .5% in NG", "not ie < 11"]})
        ]
    },
    scripts: {
        shouldTranspile: true, // set to true/false to transpile higher javascript versions
        sourceDir: "./app/web/admin-assets/js",
        sourceFiles: ["./app/web/admin-assets/js/**/*.js"],
        destinationDir: "./app/web/admin-assets/js-dist"
    },
    images: {
        shouldMinify: false,
        sourceDir: "./app/web/admin-assets/img",
        sourceFiles: "./app/web/admin-assets/img/**/*",
        destinationDir: "./" // save minified images in the same directory
    }
};


/* Add sourcemaps on all environments except production */
var generalConfig = {};
generalConfig.sourcemaps = !(environments.production());

/* Minify build files on all environments except development */
generalConfig.minify = !(environments.development());

module.exports = {feConfig, adminConfig, generalConfig};
