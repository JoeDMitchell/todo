// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var compass = require('gulp-compass');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
 
// Compile Our Sass
gulp.task('compass', function() {
  gulp.src('scss/*.scss')
    .pipe(compass({
      sass: 'scss'
    }))
    .on('error', swallowError)
    .pipe(gulp.dest('css'));
});

// Concatenate & Minify JS Vendors
gulp.task('scriptVendors', function() {
    return gulp.src([
        'js/vendor/jquery-3.0.0.min.js',
        'js/vendor/*.js',
        ])
        .pipe(concat('vendors.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('js'));
});

// Concatenate & Minify JS
gulp.task('scripts', function() {
    return gulp.src([
        'js/modules/globFunctions.js',
        'js/modules/!(init)*.js',
        'js/modules/init.js'
        ])
        .pipe(concat('site.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('js'));
});

// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('scss/**/*.scss', ['compass']);
    gulp.watch('js/modules/*.js', ['scriptVendors']);
    gulp.watch('js/modules/*.js', ['scripts']);
});

// Default Task
gulp.task('default', ['compass', 'scriptVendors', 'scripts', 'watch']);

function swallowError (error) {

  // If you want details of the error in the console
  console.log(error.toString())

  this.emit('end')
}