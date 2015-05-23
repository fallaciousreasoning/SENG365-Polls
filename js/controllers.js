(function () {
    'use strict';

    /* Controllers */

    // Temporary Database of Polls"
    var polls = [
        {
          id: 1, 
          title: '(in)sane', 
          question: 'Are you insane?',
          answers: [
            {
              option: 0,
              votes: 0,
              id: 1,
              text: "Yes" 
            },
            { option: 1,
              votes: 0,
              id: 2,
              text: "eeer *drools*"
            },
            { option: 2,
              votes: 0,
              id: 6,
              text: "There are people who aren't?"
            }           
          ]
        },
        {
          id: 2, 
          title: 'PHP', 
          question: 'Do you like php?',
          answers: [
            {
              option: 0,
              votes: 0,
              id: 3,
              text: "No" 
            },
            { option: 1,
              votes: 0,
              id: 4,
              text: "Not really"
            },
            { option: 1,
              votes: 0,
              id: 5,
              text: "Not at all"
            }          
          ]
        }
    ];

    /**
      Returns a list of all polls
    **/
    function getPolls(){
      return polls;
    }

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

    pollsControllers.controller('PollListController', ['$scope', 
        function ($scope) {
            $scope.polls = polls;
            $scope.author = 'Fred Jones';
        }]);

    pollsControllers.controller('PollController', ['$scope', '$routeParams',
      function($scope, $routeParams) {
          var pollId = $routeParams.pollId;
          $scope.poll = getPoll(pollId);
          $scope.voted = false;

          $scope.vote = function (answer) {
            answer.votes++;
            $scope.voted = true;
          };
      }]);

    pollsControllers.controller('AboutController', ['$scope',
      function ($scope){

      }]);
  }())