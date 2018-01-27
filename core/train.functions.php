<?php

	function checkTrain($trainNo, $trainDate){

		$date = date('d-m-Y', strtotime($trainDate));
		$url = "https://api.railwayapi.com/v2/live/train/$trainNo/date/$date/apikey/86j677u0rd/";
		echo $url;

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

		$res = json_decode($result, TRUE);

		$lat2 = $res["current_station"]["lat"];
		$lon2 = $res["current_station"]["lng"];

		//echo $result;

		$r = Array();

		$r["lat"] = $lat2;
		$r["lon"] = $lon2;
		$r["alldata"] = $res;

		$json = json_encode($r, JSON_UNESCAPED_SLASHES);
		//echo $json;
		return $json;

	}

	function userTrainLoc($trainNo, $trainDate, $lon1, $lat1, $tlon, $tlat){

		$date = date('d-m-Y', strtotime($trainDate));
		$url = "https://api.railwayapi.com/v2/live/train/$trainNo/date/$date/apikey/86j677u0rd/";

		echo $url;

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

		$res = json_decode($result, TRUE);

		$curLat = $res["current_station"]["lat"];
		$curLon = $res["current_station"]["lng"];

		$unit = "K";

		$dist = distance($lat1, $lon1, $tlat, $tlon, $unit);

		$nowLoc = Array('x'=> $lon1, 'y'=> $lat1);
		$lastLoc = Array('x'=> $tlon, 'y'=> $tlat);

		$r = Array();
		$r["dist"] = $dist;

		$stations = $res["route"];

		$trainPos = 0;

		$prevPos = 0;

		foreach ($stations as $s){
			if($s["has_departed"] == 1){
				$trainPos++;
			}
		}

		if ($trainPos>0)
			$prevPos = $trainPos-1;

		$nextPos = $trainPos;

		//echo "<br>Prev Station=> ".$stations[$prevPos]["station"]["name"].'<br>Next Station=> '.$stations[$nextPos]["station"]["name"].'<br>';

		$prevStation = Array('x'=> $stations[$prevPos]["station"]["lng"], 'y'=> $stations[$prevPos]["station"]["lat"]);
		$nextStation = Array('x'=> $stations[$nextPos]["station"]["lng"], 'y'=> $stations[$nextPos]["station"]["lat"]);
		$currentStation = Array('x'=> $curLon, 'y'=> $curLat);

		$anglePrevNext = getAngle($nowLoc, $prevStation, $nextStation);
		$anglePrevCur = getAngle($nowLoc, $prevStation, $currentStation);
		$angleCurLast = getAngle($nowLoc, $currentStation, $lastLoc);

		$scorePrevNext = 30;

		$negPrevNext = min(30, $anglePrevNext);
		$scorePrevNext -= $negPrevNext;

		$scorePrevCur = 45;

		$negPrevCur = min(45, $anglePrevCur*3);
		$scorePrevCur -= $negPrevCur;

		$scoreCurLast = 50;

		$negCurLast = min(50, $angleCurLast*5);
		$scoreCurLast -= $negCurLast;

		$score = exp(-0.5*$dist)*($scorePrevCur+$scorePrevNext+$scoreCurLast);
		$score /= 125.0;

		$r["anglePrevNext"] = $anglePrevNext;
		$r["anglePrevCur"] = $anglePrevCur;
		$r["angleCurLast"] = $angleCurLast;
		$r["score"] = $score;
		$r["alldata"] = $res;

		$json = json_encode($r, JSON_UNESCAPED_SLASHES);
		// echo $json;
		return $json;
	}

	function distance($lat1, $lon1, $lat2, $lon2, $unit) {

	  $theta = $lon1 - $lon2;
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	  $dist = acos($dist);
	  $dist = rad2deg($dist);
	  $miles = $dist * 60 * 1.1515;
	  $unit = strtoupper($unit);

	  if ($unit == "K") {
	    return ($miles * 1.609344);
	  } else if ($unit == "N") {
	      return ($miles * 0.8684);
	    } else {
	        return $miles;
	      }
	}

	function getAngle($po,$c, $p1) {
	    $poc = sqrt(pow($c['x']-$po['x'],2)+pow($c['y']-$po['y'],2)); // $po->$c (b)
	    $p1c = sqrt(pow($c['x']-$p1['x'],2)+pow($c['y']-$p1['y'],2)); // p1->c (a)
	    $pop1 = sqrt(pow($p1['x']-$po['x'],2)+pow($p1['y']-$po['y'],2)); // $po->p1 (c)
	    return acos(($p1c*$p1c+$poc*$poc-$pop1*$pop1)/(2*$p1c*$poc));
	}

?>