<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$api = explode('/', rawurldecode($_SERVER["REQUEST_URI"]));


switch($api[1]){
	case 'info':
		echo getSongInfo();
	break;
	
	case 'action':
		checkPianobar();
		echo sendCommand($api[2]);
	break;
}

function sendCommand($keypress){
	$unencoded = [];
	$unencoded['keypress'] = $keypress;
	
	$controlHandle = fopen('/tmp/pianobar', 'w', 1);
	if($controlHandle){
		$numBytes = fwrite($controlHandle, $keypress);
		if($numBytes === 1){
			$unencoded['sucess'] = true;
		}else{
			$unencoded['error'] = 'Writing to control file failed.';
		}
	}else{
		$unencoded['error'] = 'Could not open control file.';
	}
	fclose($controlHandle);
	$encoded = json_encode($unencoded);
	return $encoded;
}


function getSongInfo(){
	$unencoded = [];

	$infoHandle = fopen("songinfo", "r");
	if ($infoHandle) {
		while (($aLine = fgets($infoHandle)) !== false) {
			list($key, $value) = explode('=', $aLine, 2);
			$unencoded["$key"] = $value;
		}

		fclose($infoHandle);
	} else {
		$unencoded["error"] = 'Could not open file.';
	} 

	$encoded = json_encode($unencoded);
	$encoded = str_replace('\n', "", $encoded);
	return $encoded;
}

function checkPianobar(){
	$cmd = './headless_pianobar <<EOF
EOF';

	$output = [];
	$exitCode = -1;
	
	exec($cmd, $output, $exitCode);
	
}

?>