angular.module('myApp')
	.controller('registerController', registerController);

	function registerController($scope, $http){
        $scope.errors= false;
        $scope.message= "";
		$scope.userRegister = function(){
            $http.post("php/register.php", JSON.stringify($scope.user))
                .success(function(data){
                    if (!data['error']){
                        //console.log("correcto");
                        $scope.message = data["message"];
                    }else{
                        //console.log("mal");
                        //console.log(data);
                        $scope.errors = true;
                        $scope.message = data["message"];
                    }
                })
                .error(function(error){
                    $scope.errors = error;
                })
            }
	}