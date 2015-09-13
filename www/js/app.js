
angular.module('pianobarApp', ['ionic', 'ionic.utils', 'pianobarApp.controllers', 'pianobarApp.services', 'stationlist'])

.run(function($ionicPlatform) {
	$ionicPlatform.ready(function() {
		// Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
		// for form inputs)
		if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
			cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
			cordova.plugins.Keyboard.disableScroll(true);

		}
		if (window.StatusBar) {
			// org.apache.cordova.statusbar required
			StatusBar.styleLightContent();
		}
	});
})

.config(function($stateProvider, $urlRouterProvider) {
	$stateProvider

	// setup an abstract state for the tabs directive
		.state('app', {
		url: '/app',
		abstract: true,
		templateUrl: 'templates/menu.html',
		controller: 'AppCtrl'
	})
	// Each tab has its own nav history stack:
	.state('app.player', {
		url: '/player',
		views: {
			'menuContent': {
				templateUrl: 'templates/tab-player.html',
				controller: 'PlayerCtrl'
			}
		}
	})
	.state('app.stations', {
		url: '/stations',
		views: {
			'menuContent': {
				templateUrl: 'templates/tab-stations.html',
				controller: 'StationsCtrl'
			}
		}
	})
	.state('app.settings', {
		url: '/settings',
		views: {
			'menuContent': {
				templateUrl: 'templates/tab-settings.html',
				controller: 'SettingsCtrl'
			}
		}
	});

	// if none of the above states are matched, use this as the fallback
	$urlRouterProvider.otherwise('/app/player');

});

