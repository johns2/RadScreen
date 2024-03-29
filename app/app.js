var mainModule = angular.module('MainModule', ['ui.bootstrap'])

mainModule.controller('PatientsCtrl', function($scope, $http, $interval) {
    $scope.refreshList = function () {
        $http.get("controller/OutputController.php?construct=patients")
            .success(function (response) {
                $scope.data = response.records;
            });
    };

    $scope.refreshList(); //initial load
    $interval(function(){
        $scope.refreshList();
    }, 1000);

});

var characterFilter = angular.module('CharFilter', []);

characterFilter.filter('removeMinusChars', function () {
    return function (text) {
        var str = text.replace(/\-/g, '');
        return str.toLowerCase();
    };
})

var clockModule = angular.module('ClockModule', ['ds.clock'])

clockModule.controller("ClockCtrl", function ($scope) {

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
                    FeedLoader.fetch({q: feedSources[i].url, num: 5}, {}, function (data) {
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

angular.module("AllModules", ["MainModule", "FeedModule", "ClockModule", "CharFilter"]);