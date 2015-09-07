angular.module('pianobarApp.controllers', [])

/* .controller('ChatDetailCtrl', function($scope, $stateParams, Chats) {
	$scope.chat = Chats.get($stateParams.chatId);
}) */

.controller('SettingsCtrl', function($scope, $localstorage) {
	$scope.settings = {
		ipaddress : $localstorage.get('ipaddress', '192.168.999.999'),
		portnumber : parseInt($localstorage.get('portnumber', '99999'))
	};
	
	$scope.save = function (key){
		$localstorage.set(key, $scope.settings[key]);
	};
	
	
})


.controller('PlayerCtrl', function($scope, $localstorage, $http, $location) {
	$scope.songinfo = {
		artist: "",
		title: "",
		album: "",
		coverArt: "",
		stationName: "",
		songStationName: "",
		pRet: "",
		pRetStr: "",
		wRet: "",
		wRetStr: "",
		songDuration: "",
		songPlayed: "",
		rating: "",
		detailUrl: "",
		stationCount: "",
		station0: "",
		station1: "",
		station2: "",
		station3: "",
		station4: "",
		station5: "",
		station6: "",
		station7: "",
		station8: "",
		station9: "",
		station10: "",
		station11: "",
		station12: "",
		action: ""
	};
	
	$scope.ipaddress = $localstorage.get('ipaddress', '192.168.999.999');
	$scope.portnumber = $localstorage.get('portnumber', '99999');
	
	var getSongInfo = function(){
		var infourl ='http://'+$scope.ipaddress+':'+$scope.portnumber+'/info/';
			
		$http.get(infourl)
			.then(function(infoResponse){
				$scope.songinfo = infoResponse.data;
				console.log('song info reloaded');
			}, function(infoError){
				//show some error
			});
	};
	
	
	$scope.$on('$ionicView.enter', function(){
		$scope.ipaddress = $localstorage.get('ipaddress', '192.168.999.999');
		$scope.portnumber = $localstorage.get('portnumber', '99999');
		getSongInfo();
	});
	
	$scope.sendCommand = function(keypress){
		
		var actionurl ='http://'+$scope.ipaddress+':'+$scope.portnumber+'/action/'+ keypress;
		
		console.log(actionurl);
		
		$http.get(actionurl)
		.then(function(actionResponse){
			
			getSongInfo();
			
		},
		function(actionError){
			//show some error on the view.
			console.log('http failed');
		});
	};
	setInterval(function(){getSongInfo();}, 10 * 1000);
	
	$scope.getCoverArt = function(){
		var art = $scope.songinfo.coverArt;
		
		if(typeof(art) === 'undefined' || art === '')
		{
			art = 'img/nocoverart.png';
		}
		
		return art;
	};
	
	$scope.getArtist = function(){
		var artist = $scope.songinfo.artist;
		
		if(artist === undefined || artist === ''){
			artist = 'Unknown Artist';
		}
		
		return artist;
	};
	
	$scope.getTitle = function(){
		var title = $scope.songinfo.title;
		
		if(title === undefined || title === ''){
			title = 'Unknown Title';
		}
		
		return title;
	};
	
	$scope.getAlbum = function(){
		var album = $scope.songinfo.album;
		
		if(album === undefined || album === ''){
			album = 'Unknown Album';
		}
		
		return album;
	};
	
});

