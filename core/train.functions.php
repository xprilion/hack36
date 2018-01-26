<?php

	function checkTrain($trainNo){

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

	function userTrainLoc($trainNo, $lon1, $lat1){

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

		$res = json_decode($result, TRUE);

		$lat2 = $res["current_station"]["lat"];
		$lon2 = $res["current_station"]["lng"];

		$unit = "K";

		echo "lat1: $lat1, lon1: $lon1 | lat2: $lat2, lon2: $lon2<br>";
		$dist = distance($lat1, $lon1, $lat2, $lon2, $unit);
		$angle = getAngle($lat1, $lon1, $lat2, $lon2);

		echo "dist: $dist and angle : $angle";

		$r = Array();
		$r["dist"] = $dist;
		$r["angle"] = $angle;

		return $r;
	}

	$dlat = 27.2132859;
	$dlon = 78.2371117;


	$res = userTrainLoc(1, $dlon, $dlat);

	//$res = checkTrain(1);

	print "<pre>";
	print_r(json_decode($res, TRUE));
	print "</pre>";

	/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
	/*::                                                                         :*/
	/*::  This routine calculates the distance between two points (given the     :*/
	/*::  latitude/longitude of those points). It is being used to calculate     :*/
	/*::  the distance between two locations using GeoDataSource(TM) Products    :*/
	/*::                                                                         :*/
	/*::  Definitions:                                                           :*/
	/*::    South latitudes are negative, east longitudes are positive           :*/
	/*::                                                                         :*/
	/*::  Passed to function:                                                    :*/
	/*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
	/*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
	/*::    unit = the unit you desire for results                               :*/
	/*::           where: 'M' is statute miles (default)                         :*/
	/*::                  'K' is kilometers                                      :*/
	/*::                  'N' is nautical miles                                  :*/
	/*::  Worldwide cities and other features databases with latitude longitude  :*/
	/*::  are available at https://www.geodatasource.com                          :*/
	/*::                                                                         :*/
	/*::  For enquiries, please contact sales@geodatasource.com                  :*/
	/*::                                                                         :*/
	/*::  Official Web site: https://www.geodatasource.com                        :*/
	/*::                                                                         :*/
	/*::         GeoDataSource.com (C) All Rights Reserved 2017		   		     :*/
	/*::                                                                         :*/
	/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
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

	function getAngle($lat1, $lon1, $lat2, $lon2) {
	   //difference in longitudinal coordinates
	   $dLon = deg2rad($lon2) - deg2rad($lon1);

	   //difference in the phi of latitudinal coordinates
	   $dPhi = log(tan(deg2rad($lat2) / 2 + pi() / 4) / tan(deg2rad($lat1) / 2 + pi() / 4));

	   //we need to recalculate $dLon if it is greater than pi
	   if(abs($dLon) > pi()) {
	      if($dLon > 0) {
	         $dLon = (2 * pi() - $dLon) * -1;
	      }
	      else {
	         $dLon = 2 * pi() + $dLon;
	      }
	   }
	   //return the angle, normalized
	   return (rad2deg(atan2($dLon, $dPhi)) + 360) % 360;
	}

?>