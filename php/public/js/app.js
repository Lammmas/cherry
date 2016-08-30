// when we declare the app itself, we also list the dependencies
var app = angular.module('app', ['ngResource', 'ngRoute', 'ngAnimate']);

// here we are configuring the routes for the app
app.config(function($routeProvider) {
  $routeProvider
  	.when('/', {
      // each route is matched with a template and a controller
  		templateUrl: 'tpl/page-summary.html',
      controller: 'summaryController'
  	})
  	.when('/slides/:slideId', {
  		templateUrl: 'tpl/page-slide.html',
      controller: 'slideController'
  	});
});

// each controller has a name and dependencies
app.controller('summaryController', function($scope, $resource) {
  // this is where we talk to the API, by saying we have a resource in this url
  // we're also saying a part of the url is variable, the slide id
  var Slides = $resource('/slides/:slideId');
  // this get is called without a variable, and thus performs get at /slides
  $scope.slideCollection = Slides.get();
  // this variable is used to control the css of th page
  $scope.pageClass = 'page-summary';
});

// this controller requires routeParams to access the url variable
app.controller('slideController', function($scope, $resource, $routeParams) {
  var Slides = $resource('/slides/:slideId');
  // this time we're doing a get with a parameter, the slideId from the url
  $scope.slide = Slides.get({slideId:$routeParams.slideId});
  $scope.pageClass = 'page-slide';
});