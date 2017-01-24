    angular.module("game", [
        
        ])
        .controller("gameController", ['$scope','$http','$routeParams', '$location', '$route', gameController]);
        //.controller("addGameController", ['$scope','$http','$routeParams', addGameController]);
        //.controller("listGameController", ['$scope','$http','$routeParams', listGameController])

        function gameController($scope, $http, $routeParams, $location, $route){
            $scope.initChoices = function(){
                $scope.notPlayed = true;
                $scope.playing = false;
                $scope.finished = false;
                $http.post("../php/getChoices.php", JSON.stringify($routeParams))
                    .success(function(response){
                        if (!response['error']) {
                            //Se está jugando
                            if (!response['choice'].localeCompare('playing')) {
                                $scope.notPlayed = false;
                                $scope.playing = true;
                                $scope.finished = false;
                            //Se ha terminado
                            }else if (!response['choice'].localeCompare('finished')) {
                                $scope.notPlayed = false;
                                $scope.playing = false;
                                $scope.finished = true;
                            }
                        }                
                    })
                    .error(function(error){
                        $scope.errors = true;
                        $scope.message = "Hay un problema: " + error;
                    })
            }

            $scope.getGame = function(){
                $http.post("../php/getGame.php", JSON.stringify($routeParams))
                    .success(function(response){
                        //console.log(response);
                        if (!response['error']) {
                            $scope.errors = response['error'];
                            $scope.game = response['response'];
                        }else{
                            $scope.errors = response['error'];
                            $scope.message = response['message'];
                        }                
                    })
                    .error(function(error){
                        $scope.errors = true;
                        $scope.message = "Hay un problema: " + error;
                    })
                $scope.initChoices($routeParams);
            }

            $scope.setChoice = function($option){
                var $options = {gameID: $routeParams.gameID, choice: $option};
                $http.post("../php/setChoice.php", JSON.stringify($options))
                    .success(function(response){
                        if (!response['error']) {
                            //Se está jugando
                            if (!response['choice'].localeCompare('playing')) {
                                $scope.notPlayed = false;
                                $scope.playing = true;
                                $scope.finished = false;
                            //Se ha terminado
                            }else if (!response['choice'].localeCompare('finished')) {
                                $scope.notPlayed = false;
                                $scope.playing = false;
                                $scope.finished = true;
                            }
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
            //$choice tendrá valor playing, finished o notPlayed
            //$limit será el límite de juegos a mostrar
            $scope.getGamesByList = function($choice, $limit){
                $choice = typeof $choice !== 'undefined' ? $choice : 'notPlayed';
                var $options = {choice: $choice};
                $http.post("../php/getGamesByList.php", JSON.stringify($options))
                    .success(function(response){
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

            $scope.searchGamesClick = function(){
                var $searchLocation = '/gameSearch/'+$scope.searchString;
                $location.path($searchLocation);
            }

            //cambiar a $options para añadir field y search
            $scope.searchGames = function(){
                //var $options = {field: $field, searchString: $searchString};
                $http.post("../php/searchGames.php", JSON.stringify($routeParams))
                    .success(function(response){
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

            $scope.addGame = function(){
                $scope.message = "Procesando...";
                $http.post("../php/scrap.php", JSON.stringify($scope.game))
                    .success(function(response){
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

             $scope.deleteGame=function($gameID){
                 bootbox.confirm("Si acepta, se eliminará el juego seleccionado", function(result){
                       if(result==false){
                           return false;
                       }else{
                           $http.post("../php/deleteGame.php",JSON.stringify($gameID))
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
        }

        /*function addGameController($scope, $http){
            $scope.addGame = function(){
                //console.log("hola q ase");
                $http.post("../scrap.php", JSON.stringify($scope.game))
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
        }*/