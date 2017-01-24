angular.module('mainApp', [
		'ngRoute',
		'validation.match',
		'home'
		])
		.config(config);

	function config($routeProvider){
		$routeProvider
			.when('/', {
				templateUrl: '../home/home.view.html',
				controller: 'homeController'
			})

			.when('/catalog', {
				templateUrl: '../catalog/catalog.view.php',
				controller: 'catalogController'
			})

			//Games
			.when('/game/:gameID', {
				templateUrl: '../games/game.view.html',
				controller:'gameController'
			})

			.when('/addGame', {
				templateUrl: '../games/addGame.view.html',
				controller: 'gameController'
			})

			.when('/gameSearch/:searchString',{
				templateUrl: '../games/gamesSearch.view.php',
				controller: 'gameController'
			})

			//Notices
			.when('/addNotice', {
				templateUrl: '../notices/addNotice.view.html',
				controller: 'noticeController'
			})

			.when('/notices', {
				templateUrl: '../notices/notices.view.php',
				controller: 'noticeController'
			})

			.when('/notice/:noticeID', {
				templateUrl: '../notices/notice.view.html',
				controller:'noticeController'
			})

			.when('/noticeSearch/:searchString',{
				templateUrl: '../notices/noticesSearch.view.php',
				controller: 'noticeController'
			})

			//User Profile
			.when('/userProfile', {
				templateUrl: '../user/userProfile.view.html',
				controller: 'userProfileController'
			})

			.when('/log', {
				templateUrl: '../log/log.view.php',
				controller: 'logController'
			})

			.otherwise({
				redirectTo: '/'
			});
	}