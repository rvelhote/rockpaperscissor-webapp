const gulp = require('gulp');
const sass = require('gulp-sass');
const gulpWebpack = require('gulp-webpack');
const sourcemaps = require('gulp-sourcemaps');
const webpack = require('webpack');
const gutil = require('gulp-util');
const autoprefixer = require('gulp-autoprefixer');
const webpackConfig = require('./webpack.config');

const handleWebpackOutput = (err, stats) => {
    if (err) throw new gutil.PluginError('RPS', err);
    gutil.log('[RPS]', stats.toString({
        colors: true,
        chunks: false
    }));
};

const options = {
    entryPoints: { 'vendor': ['react', 'react-dom'], 'main': __dirname + '/web/src/javascript/index.js' },
    outputDir: __dirname + '/web/dist/javascript',
    plugins: [
        new webpack.optimize.CommonsChunkPlugin('vendor', 'vendor.bundle.js')
    ]
};

const sassOptions = {
    outputStyle: 'compressed',
    errLogToConsole: true
};

gulp.task('javascript:development', function() {
    gulp.src('web/src/javascript/index.js')
        .pipe(gulpWebpack(webpackConfig(options)))
        .pipe(gulp.dest('web/dist/javascript'));
});

gulp.task('sass:development', function() {
    gulp.src('web/src/sass/main.sass')
        .pipe(sourcemaps.init())
        .pipe(sass(sassOptions).on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('web/dist/css'))
});

gulp.task('default',function() {
    gulp.watch('web/src/sass/**/*.sass', ['sass:development']);
    // gulp.watch('web/src/javascript/**/*.js', ['javascript:development']);
});
