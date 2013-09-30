<?php

	require_once("db-util.php");
	$db = dbconnect();

	$route_id = $_GET['route'];

	$sql = "SELECT ride_secs, ride_to_from
			FROM rides
			WHERE route_id = $route_id 
			ORDER BY ride_date, ride_to_from";

	$result = $db->query($sql);

	$json_secs = array();
	$to_secs = array();
	$from_secs = array();

	if(!$result || mysqli_num_rows($result) == 0){
		echo ('<div class="no-rides">there was an error [' . $db->error . ']' );
	} else {
		while($row = $result->fetch_assoc()){
			if($row['ride_to_from'] == 1){
				array_push($to_secs, $row['ride_secs']);	
			} else if($row['ride_to_from'] == 0){
				array_push($from_secs, $row['ride_secs']);	
			}
			
		}
		$json_secs['to'] = $to_secs;
		$json_secs['from'] = $from_secs;
	}
	echo json_encode($json_secs);
	
	dbclose($result, $db);
?>