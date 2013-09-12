<?php 

	require_once("db-util.php");
	$db = dbconnect();

	$route_id = $_GET['route'];
	
	$sql = "SELECT * FROM rides WHERE route_id = $route_id ORDER BY ride_date";
	$result = $db->query($sql);
	if(!$result || mysqli_num_rows($result) == 0){
		echo ('<div class="no-rides">You have no rides</div>');
	} else {
		while($row = $result->fetch_assoc()){
			$date = $row['ride_date'];
			$hrs = $row['ride_hrs'];
			$mins = $row['ride_mins'];
			$secs = $row['ride_secs'];
			$to_from = $row['ride_to_from']; ?>

			<div class="clearfix project"><?php echo $date; ?></div>
<?php
		}
	}
	
	dbclose($result, $db);
?>