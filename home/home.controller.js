	function homeController($scope, $http, gameService){
		$scope.errors= false;
        $scope.prueba = "hola";
		$scope.listPending = function(){
            $http("../php/listPending.php")
                .success(function(data){
                    if (!data['error']){
                        //console.log("correcto");
                        $scope.result = data;
                        $scope.message = data['message'];
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

        $scope.funcionPrueba = function(){
            return "prueba de función";
        }

        $scope.gamefaqs = function(){
            $http.get("")
                .then(parseGame);
        }

        $scope.gamefaqs = function () {
            //var onSuccess = function (response) {
                gameService.getGame()
                             .then(function(response) {
                                        $scope.game = response;
                                   });
            //};

            //dinnerService.login($scope.username, $scope.password)
            //             .then(onSuccess);
        }
	}

    var gameService = function ($http) {

        return {
            getGame: function() {
                var parseGame = function (response) {

                    var tmp = document.implementation.createHTMLDocument();
                    tmp.body.innerHTML = response.data;

                    //var items = $(tmp.body.children).find('div.pod.pod_gameinfo div.body ul li');
                    var items = $(tmp.body.children).find('h1.page-title');

                    var game = {
                        gameTitle: $(items[0]).children('a')[0].innerText
                    }

                    /*

                    var dinners = [];
                    for (var i = 0; i < items.length; i++) {
                        var dinner = {
                            Name: $(items[i]).children('a')[0].innerText,
                            Date: $(items[i]).children('strong')[0].innerText
                        };
                        dinners.push(dinner);
                    }*/

                    return game;
                }

            return $http.get('')
                        .then(parseGame);
            }
        }
    };

    angular.module('home',[
        'games',
        'notices'])
    .factory('gameService', ['$http', gameService])
    .controller('homeController', ['$scope', '$http', 'gameService', homeController]);