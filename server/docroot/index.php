<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

$api = explode('/', rawurldecode($_SERVER["REQUEST_URI"]));


switch($api[1]){
	case '':
	case 'info':
		echo getSongInfo();
	break;
	
	case 'action':
		checkPianobar();
		echo sendCommand($api[2]);
	break;
	
	case 'station':
		echo changeStation($api[2]);
	break;
	
}

function changeStation($statNum){
	$unencoded = [];
	$unencoded['station'] = $statNum;
	
	$keypress = 's' . $statNum . PHP_EOL;
	
	$controlHandle = fopen('./keypresses', 'w', 6);
	if($controlHandle){

		if(fwrite($controlHandle, $keypress) !== false){
			$unencoded['success'] = true;
		}else{
			$unencoded['success'] = false;
			$unencoded['error'] = 'Writing to control file failed.';
		}
	}else{
		$unencoded['success'] = false;
		$unencoded['error'] = 'Could not open control file.';
	}
	fclose($controlHandle);
	$encoded = json_encode($unencoded);
	return $encoded;
	
}

function sendCommand($keypress){
	$unencoded = [];
	$unencoded['keypress'] = $keypress;
	
	$controlHandle = fopen('./keypresses', 'w', 1);
	if($controlHandle){
		$numBytes = fwrite($controlHandle, $keypress);
		if($numBytes === 1){
			$unencoded['success'] = true;
		}else{
			$unencoded['success'] = false;
			$unencoded['error'] = 'Writing to control file failed.';
		}
	}else{
		$unencoded['success'] = false;
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
	$duration = getDuration();
	
	$unencoded['remainingDuration'] = $duration[0];
	$unencoded['totalDuration'] = $duration[1];
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

function getDuration(){
	//Read last 30 bytes of the file.
	$bytes = 30;

	$fp = fopen('./out', 'r');
	fseek($fp, $bytes * -1, SEEK_END); 
	$data = fgets($fp, $bytes);
	
		
	$matches = [];
	$regex = '/-[0-9]{2}:.*$/';
	$result = preg_match($regex, $data, $matches);
	
	if($result === 1){
		$matches[0];
		list($remaining, $total) = explode('/', $matches[0], 2);
		$remaining = ltrim ($remaining, '-');
		
		list($remMins, $remSecs) = explode(':', $remaining, 2);
		$thing[] = intval($remMins) * 60 + $remSecs;
		
		list($totMins, $totSecs) = explode(':', $total, 2);
		$thing[] = intval($totMins) * 60 + $totSecs;
		
		return $thing;
	}else{
		return [0, 0];
	}
	
}

?>