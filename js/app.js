(function () {
    'use strict';

    /* App Module */

    var pollsApp = angular.module('pollsApp', [
      'ngRoute',
      'pollsControllers'
    ]);

    pollsApp.config(['$routeProvider',
      function($routeProvider) {
        $routeProvider.
          when('/polls', {
            templateUrl: 'partials/polls-list.html',
            controller: 'PollListController'
          }).
          when('/polls/:pollId', {
            templateUrl: 'partials/poll.html',
            controller: 'PollController'
          }).
          when('/about', {
            templateUrl: "partials/about.html",
            controller: 'AboutController'
          }).
          otherwise({
            redirectTo: '/polls'
          });
      }]);
}())
