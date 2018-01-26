<?php
// prevent the server from timing out
set_time_limit(0);

// include the web sockets server script (the server is started at the far bottom of this file)
require 'class.PHPWebSocket.php';
require 'db.php';
require 'user.functions.php';
require 'train.functions.php';
require 'loc.functioncs.php';

$adminID = "";

// when a client sends data to the server
function wsOnMessage($clientID, $message, $messageLength, $binary) {
	global $Server,  $adminID;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	// check if message length is 0
	if ($messageLength == 0) {
		$Server->wsClose($clientID);
		return;
	}

	if ($message == "2101996") {
		$adminID = $clientID;
		$Server->wsSend($adminID, "Welcome Admin.");
		return;
	}

	if(($clientID == $adminID) && ($message == "stop")){
		exit(0);
	}

	else{


	}
}

// when a client connects
function wsOnOpen($clientID)
{
	global $Server;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has connected." );

	// //Send a join notice to everyone but the person who joined
	// foreach ( $Server->wsClients as $id => $client )
	// 	if ( $id != $clientID )
	// 		$Server->wsSend($id, "Visitor $clientID ($ip) has joined the room.");

}

// when a client closes or lost connection
function wsOnClose($clientID, $status) {
	global $Server, $adminID;
	$ip = long2ip( $Server->wsClients[$clientID][6] );

	$Server->log( "$ip ($clientID) has disconnected." );

	// //Send a user left notice to everyone in the room
	// foreach ( $Server->wsClients as $id => $client )
	// 	if ($id != $adminID)
	// 		$Server->wsSend($id, "Visitor $clientID ($ip) has left the room.");
}

// start the server
$Server = new PHPWebSocket();
$Server->bind('message', 'wsOnMessage');
$Server->bind('open', 'wsOnOpen');
$Server->bind('close', 'wsOnClose');
// for other computers to connect, you will probably need to change this to your LAN IP or external IP,
// alternatively use: gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']))
$Server->wsStartServer('127.0.0.1', 9300);

?>