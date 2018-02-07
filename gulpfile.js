/* Import our Node.js modules */
const gulp = require('gulp'),
    cssmin = require('gulp-cssmin'),
    environments = require('gulp-environments'),
    gulpif = require('gulp-if'),
    imagemin = require('gulp-imagemin'),
    less = require('gulp-less'),
    sass = require('gulp-sass'),
    postcss = require('gulp-postcss'),
    rename = require('gulp-rename'),
    rimraf = require('rimraf'),
    sourcemaps = require('gulp-sourcemaps'),
    path = require('path'),
    config = require('./gulpfile-config');
    feConfig = config.feConfig;
    adminConfig = config.adminConfig;
    generalConfig = config.generalConfig;


/* Declare our environments */
const development = environments.development,
    staging = environments.make('staging'),
    production = environments.production;

/* Set gulp.series and gulp.parallel to constants for convenience sake */
const series = gulp.series,
    parallel = gulp.parallel;

/* Extract some config properties for convenience */
const shouldAddSourcemaps = generalConfig.sourcemaps,
    shouldMinify = generalConfig.minify;


/* Declare our gulp tasks */
gulp.task('build:admin', parallel(series(adminStyles, minifyAdmin), adminImages));
gulp.task('build:fe', parallel(series(feStyles, minifyFe), feImages));
gulp.task('build', series('build:admin', 'build:fe'));
gulp.task('default', series('build', watch));

/* Describe our gulp tasks */
gulp.task('build').description = 'Clean out the build folder then compile styles, minify images, and copy assets into the build folder';
gulp.task('build:admin').description = 'Clean out the build folder in the admin module and build';
gulp.task('build:fe').description = 'Clean out the build folder in the project root and build';
gulp.task('default').description = 'Run the build task and watch for any changes';

/**
 * This function cleans the build directory in the main project
 */
function cleanFe(done) {
    rimraf(feConfig.buildDir, done);
}
cleanFe.description = 'Cleans the build folder in the project root';

/**
 * This function cleans the build directory for the admin module
 */
function cleanAdmin(done) {
    rimraf(adminConfig.buildDir, done);
}
cleanAdmin.description = 'Cleans the build folder for the admin module';

/**
 * This function builds styles in the admin module
 */
function adminStyles() {
    return styles(adminConfig);
}

/**
 * This function builds styles in the main project
 */
function feStyles() {
    return styles(feConfig);
}

function styles(config) {
    let sConfig = config.styles,
        files = sConfig.sourceFiles,
        source = sConfig.sourceDir,
        dest = sConfig.destinationDir,
        mapsDir = sConfig.mapsDir,
        postcssConfig = sConfig.postcss;

    return compileLess(source, dest, files, mapsDir, postcssConfig);
}
styles.description = 'Compiles SCSS files to CSS; adds source maps if specified';

function minifyFe(done) {
    minifyStyles(feConfig);
    done();
}
minifyFe.description = 'Minify CSS files for the main project';

function minifyAdmin(done) {
    minifyStyles(adminConfig);
    done();
}
minifyAdmin.description = 'Minify CSS files for the admin module';

function minifyStyles(config) {
    if (shouldMinify) {
        let dir = config.styles.destinationDir;
        return minifyCSS(dir, dir, false);
    }
}
minifyStyles.description = 'Minify CSS files';


function images(config) {
    let iConfig = config.images,
        sourceDir = iConfig.sourceDir,
        destDir = iConfig.destinationDir;

    return minifyImages(sourceDir, destDir);
}
images.description = 'Minify images back into the same (source) folder';

/**
 * This function minifies images for the admin module
 */
function adminImages() {
    return images(adminConfig);
}
adminImages.description = 'Minify images for the admin module';

/**
 * This function minifies images for the main project
 */
function feImages() {
    return images(feConfig);
}
feImages.description = 'Minify images for the main project';


function watch(done) {
    if (development()) {
        var files = [path.join(adminConfig.styles.sourceDir, '**/*.less'), path.join(feConfig.styles.sourceDir, '**/*.less')];
        gulp.watch(files, styles);
    }
    done();
}
watch.description = 'Watch relevant files and re-run their tasks (only in development environment)';


/**
 * Generic function to compile LESS files
 * @param {String} sourceDir a string representing the path to the directory for the LESS files
 * @param {String} destDir a string representing the path to the directory where the compiled files would be saved
 * @param {String|Array} files string or array of strings representing the glob match for the source files
 * @param {String} mapsDir the directory where sourcemaps would be stored (relative to the destination directory)
 * @param {Array} postcssConfig an array of postcss processors
 * @returns {*} the gulp stream
 */
function compileLess(sourceDir, destDir, files, mapsDir, postcssConfig) {
    return compileStyles(sourceDir, destDir, files, mapsDir, less, postcssConfig);
}

/**
 * Generic function to compile Sass files
 * @param {String} sourceDir a string representing the path to the directory for the Sass files
 * @param {String} destDir a string representing the path to the directory where the compiled files would be saved
 * @param {String|Array} files string or array of strings representing the glob match for the source files
 * @param {String} mapsDir the directory where sourcemaps would be stored (relative to the destination directory)
 * @param {Array} postcssConfig an array of postcss processors
 * @returns {*} the gulp stream
 */
function compileSass(sourceDir, destDir, files, mapsDir, postcssConfig) {
    return compileStyles(sourceDir, destDir, files, mapsDir, function () {
        return sass().on('error', sass.logError);
    }, postcssConfig);
}

/**
 * A generic function to compile both LESS and Sass files
 * @param {String} sourceDir a string representing the path to the directory for the source files
 * @param {String} destDir a string representing the path to the directory where the compiled files would be saved
 * @param {String|Array} files string or array of strings representing the glob match for the source files
 * @param {String} mapsDir the directory where sourcemaps would be stored (relative to the destination directory)
 * @param {Function} buildFxn the build function to use. could be less or sass functions
 * @param {Array} postcssConfig an array of postcss processors
 */
function compileStyles(sourceDir, destDir, files, mapsDir, buildFxn, postcssConfig) {
    return gulp.src(files, {base: sourceDir})
        .pipe(gulpif(shouldAddSourcemaps, sourcemaps.init()))
        .pipe(buildFxn())
        .pipe(postcss(postcssConfig))
        .pipe(gulpif(shouldAddSourcemaps, sourcemaps.write(mapsDir)))
        .pipe(gulp.dest(destDir));
}

/**
 * A generic function to copy files/directories to a different directory
 * @param {String|Array} source a string or array of string representing the glob match for the source files/folders
 * @param {String} destination the new directory to copy
 */
function copy(source, destination) {
    return gulp.src(source)
        .pipe(gulp.dest(destination));
}


/**
 * Generic function to minify CSS files
 * @param {string} sourceDir a string representing the path to the directory for the source files
 * @param {string} destDir a string representing the path to the directory where the minified files would be saved
 * @param {boolean} shouldRename determine whether the new file should be renamed or not
 * @returns {*} the gulp stream
 */
function minifyCSS(sourceDir, destDir, shouldRename) {
    let files = [path.join(sourceDir, '**/*.css'), path.join('!' + sourceDir, '**/*.min.css')];
    return minify(files, destDir, cssmin, shouldRename);
}

/**
 * A generic function to minify images
 * @param {string} sourceDir a string representing the path to the directory for the source files
 * @param {string} destDir a string representing the path to the directory where the minified files would be saved
 * @returns {*} the gulp stream
 */
function minifyImages(sourceDir, destDir) {
    let files = path.join(sourceDir, '**/*');
    return minify(files, destDir, imagemin, false);
}

/**
 * A generic function to minify different types of files
 * @param {string|array} source a string or array of strings representing the glob match for the source files/folders
 * @param {string} destDir a string representing the path to the directory where the minified files would be saved
 * @param {function} minifyFxn the minify function to use
 * @param {boolean} shouldRename determine whether the new file should be renamed or not
 */
function minify(source, destDir, minifyFxn, shouldRename) {
    if ('undefined' === typeof shouldRename) {
        shouldRename = true;
    }
    return gulp.src(source)
        .pipe(minifyFxn())
        .pipe(gulpif((shouldRename), rename({suffix: '.min'})))
        .pipe(gulp.dest(destDir));
}
