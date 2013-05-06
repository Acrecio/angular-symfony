'use strict';

/* Controllers */

angular.module('myApp.controllers', []).
  controller('MyCtrl1', ['$scope', 'Hello', function($scope, Hello) {
    var hello = Hello.get({}, function(hello) {
        $scope.hello = hello;
        console.log(hello.world);        
    });
  }])
  .controller('MyCtrl2', [function() {

  }]);