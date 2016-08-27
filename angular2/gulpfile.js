var gulp = require('gulp'),
    concat = require('gulp-concat'),

    tsc = require('gulp-typescript'),
    mocha = require('gulp-mocha'),
    jsMinify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),

    scssLint = require('gulp-scss-lint'),
    sass = require('gulp-sass'),
    cssPrefixer = require('gulp-autoprefixer'),
    cssMinify = require('gulp-cssnano'),

    del = require('del'),
    merge = require('merge-stream'),
    SystemBuilder = require('systemjs-builder'),
    gulpTemplate = require('gulp-template'),
    argv = require('yargs').argv,
    ENVS = {
        DEV: 'dev',
        PROD: 'production',
        TEST: 'test'
    },
    ENV = argv.env || process.env.NODE_ENV || ENVS.DEV,
    ENV_VARS;

try {
    ENV_VARS = require('./env.' + ENV + '.json');
} catch(e) {}

gulp.task('clean', () => {
    return del('dist');
});

gulp.task('shims', () => {
    return gulp.src([
        'node_modules/core-js/client/shim.js',
        'node_modules/zone.js/dist/zone.js',
        'node_modules/reflect-metadata/Reflect.js'
    ])
        .pipe(concat('shims.js'))
        .pipe(gulp.dest('dist/js/'));
});

gulp.task('env', function () {
    return gulp.src('./src/app/shared/template/env.ts')
        .pipe(gulpTemplate({
            env: ENV_VARS || {}
        }))
        .pipe(gulp.dest('./src/app/shared'))
        .on('finish', function () {
            console.log('./src/app/shared/env.ts is generated successfully');
        });
});

gulp.task('tsc', () => {
    var tsProject = tsc.createProject('./tsconfig.json'),
    tsResult = tsProject.src().pipe(tsc(tsProject));

    return tsResult.js.pipe(gulp.dest('build/'));
});

gulp.task('system-build', [ 'tsc' ], () => {
    var builder = new SystemBuilder('.', 'system.config.js');

    return builder.buildStatic('app', 'dist/js/bundle.js', {sourceMaps: true}).then(() => del('build'));
});

gulp.task('html', () => {
    return gulp.src('src/**/**.html')
        .pipe(gulp.dest('dist/'));
});

gulp.task('js', () => {
    return gulp.src('src/**/**.js')
        .pipe(gulp.dest('dist/'));
});

gulp.task('images', () => {
    return gulp.src('src/images/**/*.*')
        .pipe(imagemin())
        .pipe(gulp.dest('dist/images/'));
});

gulp.task('lintScss', function() {
    return gulp.src('src/scss/styles.scss')
        .pipe(scssLint({ config: 'lint.yml' }));
});

gulp.task('scss', () => {
    // Copy fonts into the css folder
    gulp.src('src/scss/fonts/**/*.*').pipe(gulp.dest('dist/css/fonts/'));

    return gulp.src('src/scss/styles.scss')
        .pipe(sass({
            precision: 10,
            includePaths: 'node_modules/node-normalize-scss'
        }))
        .pipe(concat('styles.css'))
        .pipe(cssPrefixer())
        .pipe(gulp.dest('dist/css/'));
});

gulp.task('test-run', [ 'tsc' ], () => {
    return gulp.src('test/**/*.spec.js')
        .pipe(mocha());
});

gulp.task('test', [ 'test-run' ], () => {
    return del('build');
});

gulp.task('minify', () => {
    var js = gulp.src('dist/js/bundle.js')
        .pipe(jsMinify())
        .pipe(gulp.dest('dist/js/'));

var css = gulp.src('dist/css/styles.css')
    .pipe(cssMinify())
    .pipe(gulp.dest('dist/css/'));

return merge(js, css);
});

gulp.task('watch', () => {
    var watchTs = gulp.watch('src/app/**/**.ts', [ 'system-build' ]),
    watchScss = gulp.watch('src/scss/**/*.scss', [ 'lintScss', 'scss' ]),
    watchHtml = gulp.watch('src/**/*.html', [ 'html' ]),
    watchJs = gulp.watch('src/**/*.js', [ 'js' ]),
    watchImages = gulp.watch('src/images/**/*.*', [ 'images' ]),

    onChanged = function(event) {
        console.log('File ' + event.path + ' was ' + event.type + '. Running tasks...');
    };

watchTs.on('change', onChanged);
watchScss.on('change', onChanged);
watchHtml.on('change', onChanged);
watchJs.on('change', onChanged);
watchImages.on('change', onChanged);
});

gulp.task('watchtests', () => {
    var watchTs = gulp.watch('src/app/**/**.ts', [ 'test-run' ]),
    watchTests = gulp.watch('test/**/*.spec.js', [ 'test-run' ]),

    onChanged = function(event) {
        console.log('File ' + event.path + ' was ' + event.type + '. Running tasks...');
    };

watchTs.on('change', onChanged);
watchTests.on('change', onChanged);
});

gulp.task('default', [
    'shims',
    'system-build',
    'html',
    'images',
    'lintScss',
    'scss'
]);

