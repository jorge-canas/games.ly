	angular.module('home',[
		'game',
		'catalog',
		'notice',
        'userProfile',
        'log'
        ])
    .controller('homeController', ['$scope', '$http', '$routeParams', homeController]);

	function homeController($scope, $http, $routeParams){
		
	}