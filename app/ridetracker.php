<?php
	
	// load the db-util, which connects to the database
	require_once('../util/db-util.php');

	// connect. defined in db-util
	$db = dbconnect();
	// initizlize the json we'll return
	$json;
	
	// lit the routes
	if(isset($_GET['listRoutes'])){
		$sql = "SELECT route_name, route_length, route_id FROM routes";
		$result = $db->query($sql);
		if(!$result || mysqli_num_rows($result) == 0){
			$json['status'] = 0;
		} else {
			while($row = $result->fetch_assoc()){
				$name = $row['route_name'];
				$miles = $row['route_length'];
				$id = $row['route_id'];

				$json[$id]['name'] = $name;
				$json[$id]['miles'] = $miles;
			}
		}
	}

	if(isset($_GET['rideID'])){
		$id = $_GET['rideID'];

		$sql = "SELECT route_name FROM routes WHERE route_id = $id";
		$result = $db->query($sql);
		if(!$result){
			$json['status'] = 0;
		} else{
			while($row = $result->fetch_assoc()){
				$title = $row['route_name'];
				$json['name'] = $title;
			}	
		}

		$sql = "SELECT ride_date, ride_to_from, ride_secs
				FROM rides
				WHERE route_id = $id
				ORDER BY ride_date, ride_to_from";

		$result = $db->query($sql);
		
		if($result){
			$json['rides'] = array();
			while($row = $result->fetch_assoc()){
				$date = $row['ride_date'];
				$post_secs = $row['ride_secs'];
				
				if($row['ride_to_from'] == 0){
					$to_from = "&#8594;";
					$to_from_class = "to";
				} else if($row['ride_to_from'] == 1){
					$to_from = "&#8592;";
					$to_from_class = "from";
				} else {
					$to_from = "";
				}
				$ride_json['date'] = $date;
				$ride_json['secs'] = $post_secs;
				$ride_json['to_from'] = $to_from;
				$ride_json['to_from_class'] = $to_from_class;

				array_push($json['rides'], $ride_json);
			}
		}

		// overall stats
		$sql = "SELECT MAX(ride_secs), MIN(ride_secs), AVG(ride_secs)
				FROM rides WHERE route_id = $id";
		if(!$result = $db->query($sql))
			die('error with the database query - [' . $db->error . ']');
		$all = $result->fetch_assoc(); 

		$json['all_min'] = $all['MIN(ride_secs)'];
		$json['all_max'] = $all['MAX(ride_secs)'];
		$json['all_avg'] = $all['AVG(ride_secs)'];

		// to stats
		// to
		$sql = "SELECT count(ride_secs), MIN(ride_secs), MAX(ride_secs), AVG(ride_secs)
				FROM rides WHERE route_id = $id AND ride_to_from = 0";
		if(!$result = $db->query($sql))
			die('error with the database query - [' . $db->error . ']');

		$all = $result->fetch_assoc();

		$json['to_count'] = $all['count(ride_secs)'];
		$json['to_min'] = $all['MIN(ride_secs)'];
		$json['to_max'] = $all['MAX(ride_secs)'];
		$json['to_avg'] = $all['AVG(ride_secs)'];

		// from stats
		$sql = "SELECT count(ride_secs), MIN(ride_secs), MAX(ride_secs), AVG(ride_secs)
				FROM rides WHERE route_id = $id AND ride_to_from = 1";
		if(!$result = $db->query($sql))
			die('error with the database query - [' . $db->error . ']');

		$all = $result->fetch_assoc();

		$json['from_count'] = $all['count(ride_secs)'];
		$json['from_min'] = $all['MIN(ride_secs)'];
		$json['from_max'] = $all['MAX(ride_secs)'];
		$json['from_avg'] = $all['AVG(ride_secs)'];
	}
	
	// print out the json
	print_r(json_encode($json));

	dbclose($result, $db);
?>