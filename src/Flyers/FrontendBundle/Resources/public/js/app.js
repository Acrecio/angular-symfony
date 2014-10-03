'use strict';


// Declare app level module which depends on filters, and services
angular.module('myApp', ['myApp.filters', 'myApp.services', 'myApp.directives', 'myApp.controllers']).
  config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/login', {templateUrl: '/bundles/frontend/partials/login.html', controller: 'Login'});
    $routeProvider.when('/view1', {templateUrl: '/bundles/frontend/partials/partial1.html', controller: 'MyCtrl1'});
    $routeProvider.when('/view2', {templateUrl: '/bundles/frontend/partials/partial2.html', controller: 'MyCtrl2'});
    $routeProvider.otherwise({redirectTo: '/login'});
  }]);
