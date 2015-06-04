var mainModule = angular.module('MainModule', ['ui.bootstrap'])

/*app.filter('startFrom', function () {
    return function (input, start) {
        if (input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
app.controller('patientsCtrl', function ($scope, $http, $timeout) {
    $http.get('controller/PatientController.php?construct=patients').success(function (data) {
        $scope.list = data;
        $scope.currentPage = 1;
        $scope.entryLimit = 5;
        $scope.filteredItems = $scope.list.length;
        $scope.totalItems = $scope.list.length;
    });
    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function () {
        $timeout(function () {
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function (predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
});*/

mainModule.controller('PatientsCtrl', function($scope, $http, $interval) {
    $scope.refreshList = function () {
        $http.get("controller/PatientController.php?construct=patients")
            .success(function (response) {
                $scope.data = response.records;
            });
    };

    $scope.refreshList(); //initial load
    $interval(function(){
        $scope.refreshList();
    }, 10000);
});

var feeds = [];
var feedModule = angular.module('FeedModule', ['ngResource'])

feedModule.factory('FeedLoader', function ($resource) {
    return $resource('http://ajax.googleapis.com/ajax/services/feed/load', {}, {
        fetch: { method: 'JSONP', params: {v: '1.0', callback: 'JSON_CALLBACK'} }
    });
})
    feedModule.service('FeedList', function ($rootScope, FeedLoader, $interval) {
        this.get = function() {
            var feedSources = [
                {title: 'BaZ', url: 'http://bazonline.ch/rss_ticker.html'},
            ];
            if (feeds.length === 0) {
                for (var i=0; i<feedSources.length; i++) {
                    FeedLoader.fetch({q: feedSources[i].url, num: 10}, {}, function (data) {
                        var feed = data.responseData.feed;
                        feeds.push(feed);
                    });
                }
            }
            return feeds;
        };
    })
    feedModule.controller('FeedCtrl', function ($scope, FeedList) {
        $scope.$on('FeedList', function (event, data) {
            $scope.feeds = data;
        });
        $scope.feeds = FeedList.get(); //initial load

    });

feedModule.filter('time', function($filter)
{
    return function(input)
    {
        if(input == null){ return ""; }
        var _date = $filter('date')(new Date(input), 'HH:mm');
        return _date.toUpperCase();
    };
});

var refreshControl = angular.module('RefreshControl',[])

refreshControl.controller('RefreshControl',function($scope,$interval){
        $interval(function(){

        },1000);
    });

angular.module("AllModules", ["MainModule", "FeedModule"]);