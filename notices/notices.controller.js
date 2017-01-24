angular.module("notice", [
    
	    ])
	    .controller("noticeController", ['$scope','$http','$routeParams', '$location', '$route', noticeController]);

    function noticeController($scope, $http, $routeParams, $location, $route){
        $scope.getNotice = function(){
            $http.post("../php/getNotice.php", JSON.stringify($routeParams))
                .success(function(response){
                    if (!response['error']) {
                        $scope.notice = response['response'];
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

        $scope.searchNoticesClick = function(){
                var $searchLocation = '/noticeSearch/'+$scope.searchString;
                //console.log($searchLocation);
                $location.path($searchLocation);
            }

        //cambiar a $options para añadir field y search
        $scope.searchNotices = function(){
            //var $options = {field: $field, searchString: $searchString};
            $http.post("../php/searchNotices.php", JSON.stringify($routeParams))
                .success(function(response){
                    if (!response['error']) {
                        $scope.notices = response['response'];
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

        $scope.deleteNotice=function($noticeID){
            console.log($noticeID);
            bootbox.confirm("Si acepta, se eliminará la noticia seleccionada", function(result){
                if(result==false){
                    return false;
                }else{
                    $http.post("../php/deleteNotice.php",JSON.stringify($noticeID))
                        .success(function(response){
                            $scope.errors = response['error'];
                            $scope.message = response['message'];
                            $route.reload();
                        })
                        .error(function(data,status,headers,config){
                            $scope.errors = true;
                            $scope.message = "Hay un problema: " + error;
                        })
               }
            })
        }

        $scope.addNotice = function(){
            //console.log("hola q ase");
            $http.post("../php/addNotice.php", JSON.stringify($scope.notice))
                .success(function(response){
                    console.log(response['message']);
                    //console.log(response['message']);
                    if (!response['error']) {
                        $scope.errors = response['error'];
                        $scope.message = response['message'];
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

        $scope.listNotices = function(){
            $http.post("../php/listNotices.php", JSON.stringify())
                .success(function(response){
                    if (!response['error']) {
                        $scope.notices = response['response'];
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