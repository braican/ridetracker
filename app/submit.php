<?php
	
	// load the db-util, which connects to the database
	require_once('../util/db-util.php');

	// connect. defined in db-util
	$db = dbconnect();
	// initizlize the json we'll return
	$json;
	
	if(isset($_GET['addroute'])){
		$route_name = htmlspecialchars($_POST["new_route"]);
		$route_length = $_POST["length"];

		if ($route_name != "") {

			$create_table_sql =	'CREATE TABLE IF NOT EXISTS `routes`(
									`route_id` bigint(20) NOT NULL AUTO_INCREMENT,
									`route_name` text NOT NULL,
									`route_length` decimal(5,2),
									PRIMARY KEY (`route_id`)
								)';
			if(!$result = $db->query($create_table_sql)){
				die('There was an error running the add table query [' . $db->error . ']');
			}

			//clean up the input for mysql
		 	$route_name = $db->real_escape_string($route_name);
		 	$route_length = $db->real_escape_string($route_length);

		 	// check if the potential project exists in the database
		 	$sql = "SELECT COUNT( * ) FROM routes WHERE route_name = '$route_name'";
		 	$result = $db->query($sql);
			$row = $result->fetch_row();
			if($row[0] > 0){
				die('no dice, already a route with that name');
			}

			// add the new project to the project database
			$sql = "INSERT INTO routes(route_name, route_length) VALUES ('$route_name', $route_length)";

			if(!$result = $db->query($sql)){
				die('There was an error running the query in add_route [' . $db->error . ']');
			}
			echo "Route $route_name added";

		} else {
			echo "You need to add a name";
		}
	}

	if(isset($_GET['addride'])){
		// echo 'good to go';
		$date = $_POST['ride_date'];
		$route = $_POST['route_id'];
		$to_from = isset($_POST['ride_to_from']) ? $_POST['ride_to_from'] : 'NULL';

		$post_hrs = $_POST['ride_hrs'] != "" ? $_POST['ride_hrs'] : 0;
		$post_mins = $_POST['ride_mins'] != "" ? $_POST['ride_mins'] : 0;
		$post_secs = $_POST['ride_secs'] != "" ? $_POST['ride_secs'] : 0;

		$secs = $post_secs + ($post_mins * 60) + ($post_hrs * 3600);

		if($date != ""){
			$create_sql =	'CREATE TABLE IF NOT EXISTS `rides`(
								`ride_id` bigint(20) NOT NULL AUTO_INCREMENT,
								`ride_date` date NOT NULL,
								`route_id` bigint(20) NOT NULL,
								`ride_secs` bigint,
								`ride_to_from` tinyint(1),
								PRIMARY KEY (`ride_id`),
								FOREIGN KEY (route_id) REFERENCES routes(route_id)
							)';
			if(!$result = $db->query($create_sql))
				echo('there was an error adding the table [' . $db->error . ']');

			$hrs = $db->real_escape_string($hrs);
			$mins = $db->real_escape_string($mins);
			$secs = $db->real_escape_string($secs);
			$sql = "INSERT INTO rides(ride_date, route_id, ride_secs, ride_to_from)
						VALUES (
							STR_TO_DATE('".$date."', '%Y-%m-%d'),
							$route,
							$secs,
							$to_from
					);";
			// echo $sql;
			if(!$result = $db->query($sql)){
				echo('There was an error adding the date to the databse [' . $db->error . ']');
			}
		}
	}
	
	// print out the json
	//print_r(json_encode($json));

	dbclose($result, $db);
?>