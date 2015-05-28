(function () {
    'use strict';
    var restUrl= "index.php/services",
        pollsUrl = restUrl + "/polls",
        votesUrl = restUrl + "/votes",
        jsonCallback = "?callback=JSON_CALLBACK";

    var polls = [];

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
              $http.post(votesUrl + "/" + pollId + "/" + answer.optionNo + jsonCallback).success(function () {
                  $http.jsonp(votesUrl + "/" + pollId + jsonCallback).success(function(data){
                      for (var i = 0; i < data.length; ++i){
                          var votes = data[i],
                              answer = $scope.poll.answers[i];
                          answer.votes = votes;
                          $scope.voted = true;
                      }
                  });
              });
          };

          $http.jsonp(pollsUrl + "/" + pollId + jsonCallback).success(function(data){
              $scope.poll = data;
          });
      }]);

    pollsControllers.controller('AboutController', ['$scope',
      function ($scope){

      }]);

    pollsControllers.controller('AdminController', ['$scope', '$http',
        function ($scope, $http){
            $scope.polls = [];

            $scope.creating = false;
            $scope.editing = {};
            $scope.answers = []

            $scope.refreshPolls = function(){
                $http.jsonp(pollsUrl + jsonCallback).success(function(data){
                    $scope.polls = data;
                });
            };

            $scope.refreshPolls();

            $scope.delete = function (poll) {
                $http.delete(pollsUrl + '/' + poll.id).success(
                    function(){
                        $scope.refreshPolls();
                    });
            };

            $scope.reset = function (poll) {
                $http.delete(votesUrl + '/' + poll.id).success(
                    function(){
                        //Do nothing, we don't display votes here.
                    });
            };

            $scope.create = function () {
                console.log($scope.editing);
                console.log($scope.answers);

                $http.post(pollsUrl, $scope.poll).success(function() {
                    $scope.refreshPolls();
                })
            };

            $scope.beginCreate = function () {
                $scope.creating = true;
                $scope.editing = {title:"", question:""};
                $scope.answers = [];
                $scope.addAnswer();
                $scope.addAnswer();
            }

            $scope.addAnswer = function () {
                $scope.answers.push({
                    optionNo: $scope.answers.length,
                    answer: ""
                });
            }
        }]);
  }())