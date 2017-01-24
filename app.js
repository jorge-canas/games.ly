angular.module('myApp', [
		'ngRoute',
		'validation.match'
		])
		.config(config);

	function config($routeProvider){
		$routeProvider
			.when('/', {
				templateUrl: 'login/login.view.html',
				controller: 'loginController'
			})

			.when('/register', {
				templateUrl: 'register/register.view.html',
				controller: 'registerController'
			})

			.otherwise({
				redirectTo: '/'
			});
	}