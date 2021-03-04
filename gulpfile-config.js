/* Import Node.js modules */
let environments = require('gulp-environments'),
    autoprefixer = require('autoprefixer');


let config = {
    sourceDir: "./app/web",
    buildDir: "./app/web",
    sourceFiles: "./app/web/scss/**/*.scss",
    portalStyles: {
        sourceDir: "./app/web/scss",
        sourceFiles: "./app/web/scss/portal.scss",
        destinationDir: "./app/web/css",
        mapsDir: "./maps", // relative to the destination directory
        postcss: [
            autoprefixer({browsers: ["last 5 versions", "> .5% in NG", "not ie < 11"]})
        ]
    },
    siteStyles: {
        sourceDir: "./app/web/scss",
        sourceFiles: "./app/web/scss/site.scss",
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
        destinationDir: "./app/web/img" // save minified images in the same directory
    }
};

/* Add sourcemaps on all environments except production */
config.sourcemaps = !(environments.production());

/* Minify build files on all environments except development */
config.minify = !(environments.development());

module.exports = {config};
