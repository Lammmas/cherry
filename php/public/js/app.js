var app = angular.module('app', ['ngResource', 'ngRoute', 'ngAnimate', 'ngStorage']);

// here we are configuring the routes for the app
app.config(function($routeProvider) {
  $routeProvider
  	.when('/', {
      // each route is matched with a template and a controller
  		templateUrl: 'tpl/auth.html',
      controller: 'authController'
  	})
    .when('/users', {
      templateUrl: 'tpl/users.html',
      controller: 'usersController'
    })
    .when('/wallets', {
      templateUrl: 'tpl/wallets.html',
      controller: 'walletsController'
    })
    .when('/bonuses', {
      templateUrl: 'tpl/bonuses.html',
      controller: 'bonusesController'
    })
    .when('/play', {
      templateUrl: 'tpl/game.html',
      controller: 'gameController'
    });
});

// I am too tired to create separate files, in stead I'll be putting all Controllers here
// Yes, I know, copy-paste code >_>
app.controller('authController', function($scope, $location, $http, $localStorage) {
  $scope.login = function (email, password) {
    var data = {email: $scope.email, password: $scope.password};
    $scope.emailError = $scope.passwordError = '';

    $http.post('/auth', data).success(function (data, status, headers) {
      if (data.status === 'error') {
        if (data.email) $scope.emailError = data.email;
        else if (data.password) $scope.passwordError = data.password;
      } else if (data.status === 422) { // Failed verification
        if (data.validation_messages.email) $scope.emailError = data.validation_messages.email;
        else if (data.validation_messages.password) $scope.passwordError = data.validation_messages.password;
      } else {
        $localStorage.id = Number(data.user.id); // Easiest for converting to number
        $localStorage.user = data.user;
        $location.path('/play');
      }
    }).error(function (data, status, headers) {
      if (data.status === 422) { // Failed verification
        if (data.validation_messages.email) {
          for (prop in data.validation_messages.email) {
            $scope.emailError += data.validation_messages.email[prop] + ' ';
          }
        }
        else if (data.validation_messages.password) {
          for (prop in data.validation_messages.password) {
            $scope.passwordError += data.validation_messages.password[prop] + ' ';
          }
        }
      }
    });
  }
});

app.controller('usersController', function($scope, $resource, $http, $localStorage) {
  $scope.users = [];

  $http.get('/user').success(function (data, status, headers) {
    $scope.users = data._embedded.users;
  });
});

app.controller('walletsController', function($scope, $resource, $http, $localStorage) {
  $scope.wallets = [];

  $http.get('/wallet').success(function (data, status, headers) {
    $scope.wallets = data._embedded.wallets;
  });
});

app.controller('bonusesController', function($scope, $resource, $http, $localStorage) {
  $scope.bonuses = [];

  $http.get('/bonus').success(function (data, status, headers) {
    $scope.bonuses = data._embedded.bonuses;
  });
});

app.controller('gameController', function($scope, $resource, $http, $localStorage) {
  $scope.users = [];

  if ($localStorage.id) $scope.user = $localStorage.id;

  $scope.bet = 1;

  // Here should also store in LocalStorage in order to reduce the load/requests to the API
  //$scope.refresh = function () {
    $http.get('/user').success(function (data, status, headers) {
      var users = data._embedded.users, parsed = {};

      // For ease of use, parse the users into an array
      for (prop in users) {
        parsed[users[prop]['id']] = users[prop];
      }

      $scope.users = parsed;

      for (prop in users) {
        $http.get('/wallet?user_id=' + users[prop].id).success(function (data, status, headers) {
          var balance = 0, bonuses = 0, id = 0;

          for (d in data._embedded.wallets) {
            // The user_id is gonna be the same for everyone anyways
            id = data._embedded.wallets[d].user_id;

            // Only double equals, because can't be sure if it's a string or int
            if (data._embedded.wallets[d].active == 1) {
              if (data._embedded.wallets[d].bonus == 1) bonuses += Number(data._embedded.wallets[d].balance);
              else balance += Number(data._embedded.wallets[d].balance);
            }
          }

          parsed[id]['balance'] = balance;
          parsed[id]['bonus'] = bonuses;
        });
      }

      $scope.users = parsed;
    });
  //};

  //$scope.refresh();

  $scope.play = function(user, bet) {
    var data = {user_id: user, bet: bet};

    $http.post('/play', data).success(function (data, status, headers) {
      if (data.status === 'error') {
        if (data.user_id) $scope.result = data.user_id;
        else if (data.bet) $scope.result = data.bet;
      }
      else {
        if (data.victory === true) alert('You won ' + data.earnings + '$!!!');
        else alert('Sorry, you lost');

        // For some reason this doesn't work, I've got too little amount of knowledge to fix this
        //$scope.refersh();
      }
    });
  };
});