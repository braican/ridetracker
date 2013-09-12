<?php
	require_once('db-util.php');
	$date = $_POST['ride_date'];
	$route = $_POST['route_id'];
	$to_from = isset($_POST['ride_to_from']) ? $_POST['ride_to_from'] : 'NULL';

	echo $to_from;

	$hrs = $_POST['ride_hrs'] != "" ? $_POST['ride_hrs'] : 'NULL';
	$mins = $_POST['ride_mins'] != "" ? $_POST['ride_mins'] : 'NULL';
	$secs = $_POST['ride_secs'] != "" ? $_POST['ride_secs'] : 'NULL';

	if($date != ""){
		$db = dbconnect();
		$create_sql =	'CREATE TABLE IF NOT EXISTS `rides`(
							`ride_id` bigint(20) NOT NULL AUTO_INCREMENT,
							`ride_date` date NOT NULL,
							`route_id` bigint(20) NOT NULL,
							`ride_hrs` tinyint,
							`ride_mins` tinyint,
							`ride_secs` tinyint,
							`ride_to_from` tinyint(1),
							PRIMARY KEY (`ride_id`),
							FOREIGN KEY (route_id) REFERENCES routes(route_id)
						)';
		if(!$result = $db->query($create_sql))
			die('there was an error adding the table [' . $db->error . ']');

		$hrs = $db->real_escape_string($hrs);
		$mins = $db->real_escape_string($mins);
		$secs = $db->real_escape_string($secs);
		$sql = "INSERT INTO rides(ride_date, route_id, ride_hrs, ride_mins, ride_secs, ride_to_from)
					VALUES (
						STR_TO_DATE('".$date."', '%Y-%m-%d'),
						$route,
						$hrs, $mins, $secs,
						$to_from
				);";
		echo $sql;
		if(!$result = $db->query($sql)){
			die('There was an error adding the date to the databse [' . $db->error . ']');
		}

		dbclose($result, $db);
	}

?>