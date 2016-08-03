angular.module('myApp')
    .controller('loginController', loginController);

    function loginController($scope, $http, $location){
        $scope.errors= false;
        $scope.message= "";
        $scope.userLogin = function(){
            $http.post("php/login.php", JSON.stringify($scope.user))
                .success(function(response){
                    //console.log(response);
                    if (!response['error']) {
                        window.location.href ="main/main.php";
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