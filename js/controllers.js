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

            $scope.mode = "";
            $scope.creating = false;
            $scope.editing = {};
            $scope.answers = [];
            $scope.canRemoveAnswers = false;

            /**
             * Refreshes the list of polls
             */
            $scope.refreshPolls = function(){
                $http.jsonp(pollsUrl + jsonCallback).success(function(data){
                    $scope.polls = data;
                });
            };

            //Load the polls for the first time
            $scope.refreshPolls();

            /**
             * Deletes a poll
             * @param poll The poll to delete
             */
            $scope.delete = function (poll) {
                $http.delete(pollsUrl + '/' + poll.id).success(
                    function(){
                        $scope.refreshPolls();
                    });
            };

            /**
             * Resets all the votes on a poll
             * @param poll
             */
            $scope.reset = function (poll) {
                $http.delete(votesUrl + '/' + poll.id).success(
                    function(){
                        //Do nothing, we don't display votes here.
                    });
            };

            /**
             * Creates a new poll or updates
             * an existing one depending on whether
             * or not the poll being edited has an
             * id or not
             */
            $scope.create = function () {
                $scope.editing.answers = $scope.answers;

                if ($scope.mode == "create") {
                    $http.post(pollsUrl, $scope.editing).success(function () {
                        $scope.refreshPolls();
                        $scope.creating = false;
                    });
                }else{
                    $http.post(pollsUrl + "/" + $scope.editing.id, $scope.editing).success(function () {
                        $scope.refreshPolls();
                        $scope.creating = false;
                    });
                }
            };

            /**
             * Cancels creation or editing
             */
            $scope.cancel = function () {
                $scope.creating = false;
            }

            /**
             * Starts creating a new poll
             */
            $scope.beginCreate = function () {
                $scope.mode = "create";
                $scope.creating = true;
                $scope.editing = {title:"", question:""};
                $scope.answers = [];
                $scope.addAnswer();
                $scope.addAnswer();

                //Update whether we can remove answers
                $scope.canRemoveAnswers = $scope.answers.length > 2;
            }

            /**
             * Starts editing an existing poll
             * @param poll The poll to edit
             */
            $scope.edit = function (poll) {
                if (!poll) {
                    return;
                }
                $scope.editPaneTitle = "edit"
                $scope.editing = poll;
                $scope.answers = poll.answers;
                $scope.creating = true;

                //Update whether we can remove answers
                $scope.canRemoveAnswers = $scope.answers.length > 2;
            }

            /**
             * Adds an answer to the poll being edited
             */
            $scope.addAnswer = function () {
                $scope.answers.push({
                    answer: ""
                });
            }

            /**
             * Removes an existing answer from the poll
             * being edited
             * @param answer The answer to remove
             */
            $scope.removeAnswer = function (answer){
                for (var i = 0; i < $scope.answers.length; ++i){
                    console.log("Got here")
                    if ($scope.answers[i] == answer){
                        $scope.answers.splice(i, 1);
                        i--;
                        //Assume there's only one instance of this object in the array
                        break;
                    }
                }

                //Update whether we can remove answers
                $scope.canRemoveAnswers = $scope.answers.length > 2;
            }
        }]);
  }())