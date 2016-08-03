angular.module('myApp')
	.controller('registerController', registerController);

	function registerController($scope, $http){
        $scope.errors= false;
        $scope.message= "";
		$scope.userRegister = function(){
            $http.post("php/register.php", JSON.stringify($scope.user))
                .success(function(response){
                    if (!response['error']){
                        //console.log("correcto");
                        $scope.message = response["message"];
                    }else{
                        //console.log("mal");
                        //console.log(data);
                        $scope.errors = true;
                        $scope.message = response["message"];
                    }
                })
                .error(function(error){
                    $scope.errors = error;
                })
            }
	}