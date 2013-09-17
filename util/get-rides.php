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

			$mins = str_pad(floor(($post_secs % 3600) / 60), 2, '0', STR_PAD_LEFT);
			$hrs = str_pad(floor($post_secs / 3600), 2, '0', STR_PAD_LEFT);
			$secs = str_pad((($post_secs % 3600) % 60), 2, '0', STR_PAD_LEFT);
			
			if($row['ride_to_from'] == 0){
				$to_from = "To";
			} else if($row['ride_to_from'] == 1){
				$to_from = "From";
			} else {
				$to_from = "";
			}
			?>

			<div class="clearfix ride">
				<div class="date"><?php echo $date; ?></div>
				<div class="time"><?php echo $hrs . ':' . $mins . ':' . $secs; ?></div>
				<div class="to-from"><?php echo $to_from; ?></div>
			</div>
<?php
		}
	}
	
	dbclose($result, $db);
?>