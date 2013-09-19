<?php 

	require_once("db-util.php");
	$db = dbconnect();

	$route_id = $_GET['route'];
	
	$sql = "SELECT ride_date, ride_to_from, ride_secs
			FROM rides
			WHERE route_id = $route_id
			ORDER BY ride_date, ride_to_from";

	$result = $db->query($sql);
	if(!$result || mysqli_num_rows($result) == 0){
		echo ('<div class="no-rides">You have no rides</div>');
	} else {
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
			?>

			<div class="clearfix ride">
				<div class="date"><?php echo $date; ?></div>
				<div class="time"><?php outputTime($post_secs) ?></div>
				<div class="to-from <?php echo $to_from_class; ?>"><?php echo $to_from; ?></div>
			</div>
<?php
		}
	}
	
	// overall stats
	$sql =	"SELECT count(*) FROM rides WHERE route_id = $route_id";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');
	
	$count = $result->fetch_row();

	$sql =	"SELECT MAX(ride_secs) FROM rides WHERE route_id = $route_id";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');
	
	$max = $result->fetch_row();

	$sql =	"SELECT MIN(ride_secs) FROM rides WHERE route_id = $route_id";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');

	$min = $result->fetch_row();

	$sql =	"SELECT AVG(ride_secs) FROM rides WHERE route_id = $route_id";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');

	$avg = $result->fetch_row();

?>
	<div class="stats clearfix overall">
		<h2>Overall (<?php echo $count[0]; ?> rides)</h2>
		<div>best time - <?php outputTime($min[0]); ?></div>
		<div>worst time - <?php outputTime($max[0]); ?></div>
		<div>average time - <?php outputTime($avg[0]); ?></div>
	</div>
<?php

	// to
	$sql =	"SELECT count(*) FROM rides WHERE route_id = $route_id AND ride_to_from = 0";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');
	
	$count = $result->fetch_row();

	$sql =	"SELECT MAX(ride_secs) FROM rides WHERE route_id = $route_id AND ride_to_from = 0";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');
	
	$max = $result->fetch_row();

	$sql =	"SELECT MIN(ride_secs) FROM rides WHERE route_id = $route_id AND ride_to_from = 0";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');

	$min = $result->fetch_row();

	$sql =	"SELECT AVG(ride_secs) FROM rides WHERE route_id = $route_id AND ride_to_from = 0";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');

	$avg = $result->fetch_row();
?>
	<div class="stats clearfix to">
		<h2>To (<?php echo $count[0]; ?> rides)</h2>
		<div>best time - <?php outputTime($min[0]); ?></div>
		<div>worst time - <?php outputTime($max[0]); ?></div>
		<div>average time - <?php outputTime($avg[0]); ?></div>
	</div>
<?php
	// from
	$sql =	"SELECT count(*) FROM rides WHERE route_id = $route_id AND ride_to_from = 1";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');
	
	$count = $result->fetch_row();

	$sql =	"SELECT MAX(ride_secs) FROM rides WHERE route_id = $route_id AND ride_to_from = 1";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');
	
	$max = $result->fetch_row();

	$sql =	"SELECT MIN(ride_secs) FROM rides WHERE route_id = $route_id AND ride_to_from = 1";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');

	$min = $result->fetch_row();

	$sql =	"SELECT AVG(ride_secs) FROM rides WHERE route_id = $route_id AND ride_to_from = 1";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');

	$avg = $result->fetch_row();
?>
	<div class="stats clearfix from">
		<h2>From (<?php echo $count[0]; ?> rides)</h2>
		<div>best time - <?php outputTime($min[0]); ?></div>
		<div>worst time - <?php outputTime($max[0]); ?></div>
		<div>average time - <?php outputTime($avg[0]); ?></div>
	</div>
<?php 
function outputTime($post_secs){
	$mins = str_pad(floor(($post_secs % 3600) / 60), 2, '0', STR_PAD_LEFT);
	$hrs = str_pad(floor($post_secs / 3600), 2, '0', STR_PAD_LEFT);
	$secs = str_pad((($post_secs % 3600) % 60), 2, '0', STR_PAD_LEFT);

	echo '<span class="the-time">' . $hrs . ':' . $mins . ':' . $secs . '</span>';
}

dbclose($result, $db);

?>