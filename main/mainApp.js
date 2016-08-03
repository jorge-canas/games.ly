angular.module('mainApp', [
		'ngRoute',
		'validation.match',
		'notices',
		'home',
		'catalog',
		'games'
		])
		.config(config);

	function config($routeProvider){
		$routeProvider

			.when('/', {
				templateUrl: '../home/home.view.html',
				controller: 'homeController'
			})

			.when('/catalog', {
				templateUrl: '../catalog/catalog.view.html',
				controller: 'catalogController'
			})

			.when('/insertGame', {
				templateUrl: '../games/insertGame.view.html',
				controller: 'gamesController'
			})

			.when('/notices', {
				templateUrl: '../notices/notices.view.html',
				controller: 'noticesController'
			})			

			.otherwise({
				redirectTo: '/'
			});
	}