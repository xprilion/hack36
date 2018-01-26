<?php

	function checkTrain($trainNo){

		//global $mysqli;

		// $cSession = curl_init();
		// //step2
		// curl_setopt($cSession,CURLOPT_URL,"https://api.railwayapi.com/v2/live/train/12046/date/26-01-2018/apikey/86j677u0rd/");
		// curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
		// curl_setopt($cSession,CURLOPT_HEADER, false);
		// //step3
		// $result=curl_exec($cSession);
		// //step4
		// curl_close($cSession);

		// return $result;

		$url = 'https://api.railwayapi.com/v2/live/train/12987/date/25-01-2018/apikey/86j677u0rd/';
		$proxy = '172.31.52.54:3128';
		$proxyauth = 'edcguest:edcguest';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		//echo $result;

		return $result;

	}

	$res = checkTrain(1);

	print "<pre>";
	print_r(json_decode($res, TRUE));
	print "</pre>";
?>