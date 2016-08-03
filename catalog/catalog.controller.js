angular.module('catalog', [
    
    ])
    .controller('catalogController', ['$scope', '$http', '$routeParams', catalogController]);

    function catalogController($scope, $http, $routeParams){
        $scope.getGameCatalog = function($limit){
            $limit = typeof $limit !== 'undefined' ? $limit : 0;
            var $options = {limit: $limit};
            $http.post("../php/listGames.php", JSON.stringify($options))
            .success(function(response){
                //console.log(response);
                if (!response['error']) {
                    $scope.games = response['response'];
                }else{
                    $scope.errors = response['error'];
                    $scope.message = response['message'];
                }
            })
            .error(function(error){
                $scope.errors = true;
                $scope.message = "Hay un problema: " + error;
            })
        }
    }