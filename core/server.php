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
	global $Server,  $mysqli;
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

			$thash = md5($data["trainNo"]);

			$trainLat = 0.0;
			$trainLon = 0.0;

			$trainIs = 0;

			$sql = "SELECT * FROM train_loc where thash = '$thash'";
			if($result = $mysqli->query($sql)){
				if ($result->num_rows > 0) {
					$res = $result->fetch_assoc();
					$trainLat = $res["lat"];
					$trainLon = $res["lon"];
					$trainIs = 1;
				}
			}

		}
		else if ($data["onTrain"] == "yes") {
			$clientLon = $data["lon"];
			$clientLat = $data["lat"];

			$thash = md5($data["trainNo"]);

			$trainLat = 0.0;
			$trainLon = 0.0;

			$trainIs = 0;

			$sql = "SELECT * FROM train_loc where thash = '$thash'";
			if($result = $mysqli->query($sql)){
				if ($result->num_rows > 0) {
					$res = $result->fetch_assoc();
					$trainLat = $res["lat"];
					$trainLon = $res["lon"];
					$trainIs = 1;
				}
			}

			$info = userTrainLoc($data["trainNo"], $clientLon, $clientLat, $trainLon, $trainLat);

			$infoScore = $info["score"];
			$infoLat = $info["lat"];
			$infoLon = $info["lon"];

			$newScore = 0.0;

			$sql = "SELECT * FROM clients where chash='$chash' AND thash = '$thash'";
			if($result = $mysqli->query($sql)){

				$sqlClient = "";

				if ($result->num_rows > 0) {
					$res = $result->fetch_assoc();

					$score = $res["score"];

					$newScore = 0.80*$score;
					if($infoScore>=$score){
						$newScore += 0.25*$infoScore;
					}
					else{
						$newScore -= 0.25*$infoScore;
					}

					$sqlClient = "UPDATE clients SET score='$newScore', lat='$infoLat', lon = '$infoLon'";

					//QUERY

				}
				else{
					$sqlClient = "INSERT INTO clients (chash, thash, lat, lon, score) VALUES ('$chash', '$thash', '$clientLat', $clientLon', $infoScore)";

					//QUERY
				}

				$mysqli->query($sqlClient);
			}

			$diffLat = $trainLat - $clientLat;
			$newLat = $trainLat+$newScore*$diffLat;

			$diffLon = $trainLon - $clientLon;
			$newLon = $trainLon+$newScore*$diffLon;

			$sqlTrain = "";

			if($trainIs){
				$sqlTrain = "UPDATE train_loc SET lat='$newLat' AND lon='$newLon' WHERE thash='$thash'";
			}
			else{
				$sqlTrain = "INSERT INTO train_loc (thash, lat, lon) VALUES ('$thash', '$newLat', '$newLon')";
			}

			$mysqli->query($sqlTrain);

			$res["answer"] = $info["alldata"];
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