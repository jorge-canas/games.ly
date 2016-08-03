angular.module('games', [])
	.controller('gamesController', gamesController);

	function gamesController($scope, $http){
		$scope.errors= false;
		$scope.insertGame = function(){
            $http.post("../php/insertGame.php", JSON.stringify($scope.game))
                .success(function(data){
                    if (!data['error']){
                        //console.log("correcto");
                        $scope.result = data["message"];
                    }else{
                        //console.log("mal");
                        //console.log(data);
                        $scope.errors = true;
                        $scope.result = data["message"];
                    }
                })
                .error(function(error){
                    $scope.errors = error;
                })
            }
	}