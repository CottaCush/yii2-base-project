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
    babel = require("gulp-babel"),
    rimraf = require('rimraf'),
    sourcemaps = require('gulp-sourcemaps'),
    path = require('path'),
    gulpConfig = require('./gulpfile-config');
portalConfig = gulpConfig.config.portalStyles;
siteConfig = gulpConfig.config.siteStyles;


/* Declare our environments */
const development = environments.development,
    staging = environments.make('staging'),
    production = environments.production;

/* Set gulp.series and gulp.parallel to constants for convenience sake */
const series = gulp.series,
    parallel = gulp.parallel;

/* Extract some config properties for convenience */
const shouldAddSourcemaps = gulpConfig.sourcemaps,
    shouldMinify = gulpConfig.minify;


/* Declare our gulp tasks */
gulp.task('build:portal', parallel(series(portalStyles, minifyPortal), images));
gulp.task('build:site', parallel(series(siteStyles, minifySite), images));
gulp.task('build', series('build:site', 'build:portal'));
gulp.task('default', series('build', watch));

/* Describe our gulp tasks */
gulp.task('build').description = 'Clean out the build folder then compile styles, minify images, and copy assets into the build folder';
gulp.task('build:portal').description = 'Clean out the build folder in the portal module and build';
gulp.task('build:site').description = 'Clean out the build folder in the project root and build';
gulp.task('default').description = 'Run the build task and watch for any changes';


function clean(done)
{
    rimraf(gulpconfig.buildDir, done);
}
clean.description = 'Cleans the build folder in the project root';

function portalStyles()
{
    return styles(portalConfig);
}

function siteStyles()
{
    return styles(siteConfig);
}

function styles(config)
{
    let sConfig = config,
        files = sConfig.sourceFiles,
        source = sConfig.sourceDir,
        dest = sConfig.destinationDir,
        mapsDir = sConfig.mapsDir,
        postcssConfig = sConfig.postcss;

    return compileSass(source, dest, files, mapsDir, postcssConfig);
}
styles.description = 'Compiles SCSS files to CSS; adds source maps if specified';

function minifySite(done)
{
    minifyStyles(siteConfig);
    done();
}
minifySite.description = 'Minify CSS files for the main project';

function minifyPortal(done)
{
    minifyStyles(portalConfig);
    done();
}
minifyPortal.description = 'Minify CSS files for the portal module';

function minifyStyles(config)
{
    if (shouldMinify) {
        let dir = config.styles.destinationDir;
        return minifyCSS(dir, dir, false);
    }
}
minifyStyles.description = 'Minify CSS files';

function images()
{
    let iConfig = gulpConfig.config.images,
        sourceDir = iConfig.sourceDir,
        destDir = iConfig.destinationDir;

    return minifyImages(sourceDir, destDir);
}
images.description = 'Minify images back into the same (source) folder';

function buildScripts(config)
{
    return gulp.src(config.scripts.sourceFiles)
        .pipe(babel({
            presets: [
                [
                    '@babel/preset-env',
                    {
                        // Specify minimum browser versions supported. Transpiles to es2015 if no targets are provided
                        // https://babeljs.io/docs/en/babel-preset-env#how-does-it-work
                        "targets": {},
                        "modules": false  // toggle to use strict mode in generated es2015
                    }
                ]
            ]
        }))
        .pipe(gulp.dest(config.scripts.destinationDir));
}
buildScripts.description = 'Minify scripts back into the same (source) folder';

function watch(done)
{
    if (development()) {
        gulp.watch(gulpConfig.config.sourceFiles, styles);
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
function compileLess(sourceDir, destDir, files, mapsDir, postcssConfig)
{
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
function compileSass(sourceDir, destDir, files, mapsDir, postcssConfig)
{
    return compileStyles(sourceDir, destDir, files, mapsDir, function () {
        return sass({outputStyle: 'expanded'}).on('error', sass.logError);
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
function compileStyles(sourceDir, destDir, files, mapsDir, buildFxn, postcssConfig)
{
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
function copy(source, destination)
{
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
function minifyCSS(sourceDir, destDir, shouldRename)
{
    let files = [path.join(sourceDir, '**/*.css'), path.join('!' + sourceDir, '**/*.min.css')];
    return minify(files, destDir, cssmin, shouldRename);
}

/**
 * A generic function to minify images
 * @param {string} sourceDir a string representing the path to the directory for the source files
 * @param {string} destDir a string representing the path to the directory where the minified files would be saved
 * @returns {*} the gulp stream
 */
function minifyImages(sourceDir, destDir)
{
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
function minify(source, destDir, minifyFxn, shouldRename)
{
    if ('undefined' === typeof shouldRename) {
        shouldRename = true;
    }
    return gulp.src(source)
        .pipe(minifyFxn())
        .pipe(gulpif((shouldRename), rename({suffix: '.min'})))
        .pipe(gulp.dest(destDir));
}
