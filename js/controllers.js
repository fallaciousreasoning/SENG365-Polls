(function () {
    'use strict';
    var restUrl= "index.php/services",
        pollsUrl = restUrl + "/polls",
        votesUrl = restUrl + "/votes",
        jsonCallback = "?callback=JSON_CALLBACK";

    var polls = [];

    /**
      Gets a poll from the database via an Id
    **/
    function getPoll(id){
      // fetch the item from the "database"
      for (var i = 0; i < polls.length; ++i) {
          if (polls[i].id == id) {
              return polls[i];
          }
      }
      console.log('no such poll as ' + id);
    }

    var pollsControllers = angular.module('pollsControllers', []);

    pollsControllers.controller('PollListController', ['$scope', '$http',
        function ($scope, $http) {
            $scope.polls = polls;
            $scope.author = 'Fred Jones';

            $http.jsonp(pollsUrl + jsonCallback).success(function(data){
                $scope.polls = data;
            });
        }]);

    pollsControllers.controller('PollController', ['$scope', '$http', '$routeParams',
      function($scope, $http, $routeParams) {
          var pollId = $routeParams.pollId;
          $scope.poll;
          $scope.voted = false;

          $scope.vote = function (answer) {
            //TODO
          };

          $http.jsonp(pollsUrl + "/" + pollId + jsonCallback).success(function(data){
              $scope.poll = data;
          });
      }]);

    pollsControllers.controller('AboutController', ['$scope',
      function ($scope){

      }]);
  }())