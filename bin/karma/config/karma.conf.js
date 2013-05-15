basePath = '../';

files = [
  JASMINE,
  JASMINE_ADAPTER,
  '../../web/bundles/frontend/lib/angular/angular.js',
  '../../web/bundles/frontend/lib/angular/angular-*.js',
  'test/lib/angular/angular-mocks.js',
  '../../web/bundles/frontend/js/**/*.js',
  'test/unit/**/*.js'
];

autoWatch = true;

browsers = ['Chrome'];

junitReporter = {
  outputFile: 'test_out/unit.xml',
  suite: 'unit'
};
