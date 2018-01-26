<?php

	include('socket.client.php');

	$ws = new ws(array
	(
		'host' => '127.0.0.1',
		'port' => 9300,
		'path' => ''
	));

	$result = $ws->send('message');
	$ws->close();

	echo $result;



?>