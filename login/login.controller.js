angular.module('myApp')
    .controller('loginController', loginController);

    function loginController($scope, $http, $location){
        $scope.errors= false;
        $scope.message= "";
        $scope.userLogin = function(){
            $http.post("php/login.php", JSON.stringify($scope.user))
                .success(function(data){
                    console.log(data);
                    if (!data['error']) {
                        window.location.href ="main/main.php";
                    }else{
                        $scope.errors = data['error'];
                        $scope.message = data['message'];
                    }                
                })
                .error(function(error){
                    $scope.errors = "Hay un problema: " + error;
                })
            }
    }