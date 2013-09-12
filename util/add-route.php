<?php 
	require_once("db-util.php");
	$route_name = htmlspecialchars($_POST["new_route"]);
	$route_length = $_POST["length"];

	if ($route_name != "") {
		//connect to database
		$db = dbconnect();

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

		dbclose($result, $db);
	} else {
		echo "You need to add a name";
	}
?>