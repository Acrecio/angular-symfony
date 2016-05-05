var gulp = require('gulp');
var nodemon = require('gulp-nodemon');

gulp.task('develop', function () {
  nodemon({script: './index.js', ext: 'js hjs json', legacyWatch: true });
});

gulp.task('dist', function() {
  gulp.src('src/*')
    .pipe(gulp.dest('dist/'))
});

gulp.task('default', ['develop', 'dist']);
