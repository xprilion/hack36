<?php
// prevent the server from timing out
set_time_limit(0);

require 'class.PHPWebSocket.php';
require 'db.php';
require 'user.functions.php';
require 'train.functions.php';
require 'loc.functions.php';

$adminID = "";

function wsOnMessage($clientID, $message, $messageLength, $binary) {
	global $Server,  $adminID;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$chash = md5($ip);

	$data = json_decode($message, TRUE);

	if ($messageLength == 0) {
		$Server->wsClose($clientID);
		return;
	}

	$res = array();

	$restr = "";

	if($data["type"]=="admin"){

		if(($data["load"] == "stop")){
			exit(0);
		}

	}
	else if($data["type"]=="train"){


		if ($data["onTrain"] == "no") {
			$res["answer"] = checkTrain($data["trainNo"]);
		}
		else if ($data["onTrain"] == "yes") {
			$long = $data["lon"];
			$lat = $data["lat"];
			$score = -999;

			$thash = md5($data["trainNo"]);

			$sql = "SELECT * FROM clients where chash='$chash' AND thash = '$thash'";
			if($result = $mysqli->query($sql)){
				if ($result->num_rows > 0) {
					$score = $result["score"];
				}
			}

			$distAng = userTrainLoc($data["trainNo"], $lon, $lat);


		}

		$restr = json_encode($res);
		$Server->wsSend($clientID, $restr);
	}
}

function wsOnOpen($clientID)
{
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has connected." );

}

function wsOnClose($clientID, $status) {
	global $Server, $adminID;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has disconnected." );

}

$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');

$Server->wsStartServer('127.0.0.1', 9300);

?>