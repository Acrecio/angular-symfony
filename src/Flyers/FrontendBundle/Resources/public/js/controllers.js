'use strict';

/* Controllers */

angular.module('myApp.controllers', []).
  controller('MyCtrl1', ['$scope', 'Auth', function($scope, Auth) {
    Auth.clearCredentials();
    Auth.setCredentials('admin', 'adminpass');
    // Auth.get('http://localhost/webservices/app_dev.php/api/hello').success(function(data){
    //   console.log(data);
    // })
  }])
  .controller('MyCtrl2', [function() {

  }]);