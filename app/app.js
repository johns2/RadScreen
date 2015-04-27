var app = angular.module('RadScreen', ['ui.bootstrap']);

app.filter('startFrom', function () {
    return function (input, start) {
        if (input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
app.controller('patientsCtrl', function ($scope, $http, $timeout) {
    $http.get('controller/PatientController.php').success(function (data) {
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
});