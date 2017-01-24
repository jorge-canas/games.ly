	angular.module('log',[
		
        ])
    .controller('logController', ['$scope', '$http', '$routeParams', logController]);

	function logController($scope, $http, $routeParams){
		$scope.getLog=function(){
	        //$http.post("readLog.php",JSON.stringify())
	        $http.get("../php/readLog.php")
	            .success(function(response){
                    if (!response['error']) {
                        $scope.logLines = response['response'];
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