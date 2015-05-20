var trivialApp = angular.module("trivialApp", ['ngRoute']);


trivialApp.config(function($routeProvider){
	$routeProvider.
	when('/users', {
		templateUrl : './partials/users.html',
		controller : 'userController'
	}).
	when('/teams', {
		templateUrl: './partials/teams.html',
		controller: 'teamController'
	}).
	when('/matches',{
		templateUrl : './partials/matches.html',
		controller : 'matchController'
	}). 
	when('/rankings',{
		templateUrl : './partials/rankings.html',
		controller: 'rankingController'
	}).
	when('/questions',{
		templateUrl : './partials/questions.html',
		controller: 'questionController'
	}).
	when('/search',{
		templateUrl : './partials/search.html',
		controller: 'searchController'
	}).
	when('/play', { 
		templateUrl: './partials/play.html',
		controller: 'playController'
	}).	
	otherwise({
		redirectTo: '/'
	});
});