angular.module('userProfile',[
		
        ])
    .controller('userProfileController', ['$scope', '$http', userProfileController]);

	function userProfileController($scope, $http){
		$scope.changeMyPassword = function(){
			//console.log('cambio password');
			$scope.user.choice = 'password';
			$scope.changeParameters($scope.user);
		}

		$scope.changeMyEmail = function(){
			//console.log('cambio correo');
			$scope.user.choice = 'email';
			$scope.changeParameters($scope.user);
		}

		$scope.changeParameters = function ($options){
			//console.log('cambio parámetros');
			if (typeof $options !== 'undefined') {
				$http.post("../php/userProfile.php", JSON.stringify($options))
	                .success(function(response){
	                	console.log(response);
	                    if (!response['error']){
	                        //console.log("correcto");
	                        $scope.errors = false;
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
	}