const { src, dest, watch, parallel, series } = require('gulp');
const concat = require('gulp-concat');
const plumber = require('gulp-plumber');
const uglify = require('gulp-uglify');
const sass = require('gulp-sass');
const rename = require('gulp-rename');
const babel = require('gulp-babel');
const rmLines = require('gulp-rm-lines');
const browserSync = require('browser-sync').create();

function copyPromisePolyfill() {
  return src('node_modules/promise-polyfill/dist/polyfill.min.js')
    .pipe(rename('promise-polyfill.min.js'))
    .pipe(dest('public/assets/slim-ui/src/js/polyfills'))
}

function copyFetchPolyfill() {
  return src('node_modules/unfetch/polyfill/index.js')
    .pipe(rename('fetch-polyfill.min.js'))
    .pipe(dest('public/assets/slim-ui/src/js/polyfills'))
}

function copyArrayIncludesPolyfill() {
  return src('node_modules/polyfill-array-includes/array-includes.js')
    .pipe(rename('array-includes-polyfill.js'))
    .pipe(dest('public/assets/slim-ui/src/js/polyfills'))
}

function copyEventEmitter() {
  return src('node_modules/mitt/dist/mitt.es.js')
    .pipe(rename('mitt.js'))
    .pipe(rmLines({
      'filters': [
        /export default\s/i,
        /# sourceMappingURL/i,
      ]
    }))
    .pipe(dest('public/assets/slim-ui/src/js/libraries'))
}

function compileSCSS() {
  return src(['public/assets/slim-ui/src/scss/slim-ui.scss'])
    .pipe(sass({ outputStyle: 'compressed' }))
    .pipe(rename('slim-ui.min.css'))
    .pipe(dest('public/assets/slim-ui/assets/css'))
    .pipe(browserSync.stream());
}

function minifyJS() {
  return src([
    'public/assets/slim-ui/src/js/polyfills/*.js',
    'public/assets/slim-ui/src/js/libraries/*.js',
    'public/assets/slim-ui/src/js/slim-ui.js'
  ])
    .pipe(plumber())
    .pipe(concat('slim-ui.min.js'))
    .pipe(babel({
      presets: ['@babel/preset-env']
    }))
    .pipe(uglify())
    .pipe(dest('public/assets/slim-ui/assets/js'))
    .pipe(browserSync.reload({ stream: true }));
}

function minifyScripts() {
  return src([
    'public/assets/slim-ui/src/js/*.js',
    '!public/assets/slim-ui/src/js/slim-ui.js',
  ])
    .pipe(plumber())
    .pipe(babel({
      presets: ['@babel/preset-env']
    }))
    .pipe(uglify())
    .pipe(rename({
      suffix: '.min'
    }))
    .pipe(dest('public/assets/slim-ui/assets/js'))
    .pipe(browserSync.reload({ stream: true }));
}

function serveBrowserSync(cb) {
  browserSync.init({
    proxy: "survey-backend.local",
    notify: true,
  });

  cb();
}

function streamAssets(cb) {
  browserSync.stream();
  cb();
}

function reloadStream(cb) {
  browserSync.reload({ stream: true });
  cb();
}

function reloadPage(cb) {
  browserSync.reload();
  cb();
}

function copyPolyfills() {
  copyPromisePolyfill();
  copyFetchPolyfill();
  copyArrayIncludesPolyfill();
}

function copyLibraries() {
  copyEventEmitter();
}

function watchChanges(cb) {
  watch(['public/assets/slim-ui/src/js/polyfills/*.js', 'public/assets/slim-ui/src/js/libraries/*.js', 'public/assets/slim-ui/src/js/slim-ui.js'], parallel(minifyJS));
  watch(['public/assets/slim-ui/src/js/*.js', '!public/assets/slim-ui/src/js/slim-ui.js'], parallel(minifyScripts));
  watch(['public/assets/slim-ui/src/scss/*.scss', 'public/assets/slim-ui/src/scss/**/*.scss'], parallel(compileSCSS));
  watch(['public/assets/slim-ui/*.html', 'public/assets/slim-ui/assets/images/*'], parallel(reloadPage));
  
  watch(['**/*.php', 'public/assets/**/images/*'], parallel(reloadPage));

  cb();
}

function main(cb) {
  copyPolyfills();
  copyLibraries();
  minifyJS();
  minifyScripts();
  compileSCSS();

  cb();
}

exports.default = parallel(serveBrowserSync, main, watchChanges);