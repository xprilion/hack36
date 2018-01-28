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
	global $Server,  $db;
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

		$trainNo = $data["trainNo"];
		$trainDate = $data["date"];

		//echo $trainNo;

		if ($data["onTrain"] == "no") {

			$info = json_decode(checkTrain($trainNo, $trainDate), TRUE);
			$ret["answer"] = $info;

			$infoLat = $info["lat"];
			$infoLon = $info["lon"];

			$thash = md5($trainNo.$trainDate);

			$trainLat = 0.0;
			$trainLon = 0.0;

			$trainIs = 0;

			$sql = "SELECT * FROM train_loc where thash = '$thash'";

			if($result = mysqli_query($db, $sql)){
				if(mysqli_num_rows($result)>0){
					$res = mysqli_fetch_assoc($result);
					$trainLat = $res["lat"];
					$trainLon = $res["lon"];
					$trainIs = 1;
				}
			}
			else{
				echo mysqli_error($db);
			}

			$sqlTrain = "";

			if($trainIs){
				$sqlTrain = "UPDATE train_loc SET lat='$infoLat' AND lon='$infoLon' WHERE thash='$thash'";
			}
			else{
				$sqlTrain = "INSERT INTO train_loc (thash, lat, lon) VALUES ('$thash', '$infoLat', '$infoLon')";
			}

			echo '\n'.$sqlTrain.'\n';
			if(mysqli_query($db, $sqlTrain)){
				;
			}
			else{
				echo mysqli_error($db);
			}

		}
		else if ($data["onTrain"] == "yes") {
			$clientLon = $data["longitude"];
			$clientLat = $data["latitude"];

			$thash = md5($trainNo.$trainDate);

			$trainLat = 0.0;
			$trainLon = 0.0;

			$trainIs = 0;

			$sql = "SELECT * FROM train_loc where thash = '$thash'";

			if($result = mysqli_query($db, $sql)){
				if(mysqli_num_rows($result)>0){
					$res = mysqli_fetch_assoc($result);
					$trainLat = $res["lat"];
					$trainLon = $res["lon"];
					$trainIs = 1;
				}
			}
			else{
				echo mysqli_error($db);
			}

			$info = json_decode(userTrainLoc($trainNo, $trainDate, $clientLon, $clientLat,  $trainLon, $trainLat, $trainIs), TRUE);

			$infoLat = $info["lat"];
			$infoLon = $info["lon"];

			//print_r($info);

			$infoScore = $info["score"];

			echo "|".$infoScore."|";

			$newScore = 0.0;

			$sql = "SELECT * FROM clients where chash='$chash' AND thash = '$thash'";

			if($result = mysqli_query($db, $sql)){

				$sqlClient = "";

				if (mysqli_num_rows($result)>0) {
					$res = mysqli_fetch_assoc($result);

					$score = $res["score"];
					$score = round($score,15);

					$newScore = 0.80*$score;
					if($infoScore>=$score){
						$newScore += 0.25*$infoScore;
					}
					else{
						$newScore -= 0.25*$infoScore;
					}

					$newScore = round($newScore,15);

					$sqlClient = "UPDATE clients SET score='$newScore', lat='$clientLat', lon = '$clientLon' WHERE chash = '$chash' AND thash='$thash'";

					//QUERY

				}
				else{
					$infoScore = round($infoScore,15);

					$sqlClient = "INSERT INTO clients (chash, thash, lat, lon, score) VALUES ('$chash', '$thash', '$clientLat', '$clientLon', '$infoScore')";

					//QUERY
				}

				echo '\n'.$sqlClient.'\n';
				if(mysqli_query($db, $sqlClient)){
					;
				}
				else{
					echo "Client UPDATE ERROR: $sqlClient -> ";
					echo mysqli_error($db);
				}
			}else{
				echo "Client SELECT ERROR: ";
				echo mysqli_error($db);
			}

			if($trainIs==0){
				$trainLat = $infoLat;
				$trainLon = $infoLon;
			}

			$diffLat = $trainLat - $clientLat;
			$newLat = $trainLat+$newScore*$diffLat;

			$diffLon = $trainLon - $clientLon;
			$newLon = $trainLon+$newScore*$diffLon;

			$sqlTrain = "";

			if($trainIs){
				$sqlTrain = "UPDATE train_loc SET lat='$newLat', lon='$newLon' WHERE thash='$thash'";
			}
			else{
				$sqlTrain = "INSERT INTO train_loc (thash, lat, lon) VALUES ('$thash', '$newLat', '$newLon')";
			}

			echo '\n'.$sqlTrain.'\n';
			if(mysqli_query($db, $sqlTrain)){
				;
			}
			else{
				echo mysqli_error($db);
			}

			$ret["answer"] = $info;
		}

		$restr = json_encode($ret, JSON_UNESCAPED_SLASHES);
		//echo $restr;
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