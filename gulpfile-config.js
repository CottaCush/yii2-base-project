/* Import Node.js modules */
var environments = require('gulp-environments'),
    autoprefixer = require('autoprefixer');


var config = {
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
        sourceDir: "./app/web/js",
        sourceFiles: ["./app/web/js/**/*.js"],
        destinationDir: "./app/web/js"
    },
    images: {
        sourceDir: "./app/web/img",
        sourceFiles: "./app/web/img/**/*",
        destinationDir: "./app/web/img"
    }
};

/* Add sourcemaps on all environments except production */
config.sourcemaps = !(environments.production());

/* Minify build files on all environments except development */
config.minify = !(environments.development());


module.exports = config;
