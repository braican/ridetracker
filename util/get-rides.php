<?php 

	require_once("db-util.php");
	$db = dbconnect();

	$route_id = $_GET['route'];
	
	$sql = "SELECT ride_date, ride_to_from, ride_secs
			FROM rides
			WHERE route_id = $route_id
			ORDER BY ride_date, ride_to_from";

	$result = $db->query($sql);
	if(!$result || mysqli_num_rows($result) == 0) :
		echo ('<div class="no-rides">You have no rides</div>');
	else : ?>
	<div class="ride-row-container">
	<?php
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
<?php } ?>
	</div>
<?php endif; ?>
	
<?php
	// overall stats
	$sql = "SELECT count(ride_secs), MAX(ride_secs), MIN(ride_secs), AVG(ride_secs)
			FROM rides WHERE route_id = $route_id";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');
	$all = $result->fetch_assoc(); ?>
	<div class="stats clearfix overall">
		<h3>Overall (<?php echo $all['count(ride_secs)']; ?> rides)</h3>
		<div class="the-stats">
			<div><div>best time</div> <div><?php outputTime($all['MIN(ride_secs)']); ?></div></div>
			<div><div>worst time</div> <div><?php outputTime($all['MAX(ride_secs)']); ?></div></div>
			<div><div>average time</div> <div><?php outputTime($all['AVG(ride_secs)']); ?></div></div>
		</div>
	</div>


<?php
	// to
	$sql = "SELECT count(ride_secs), MIN(ride_secs), MAX(ride_secs), AVG(ride_secs)
			FROM rides WHERE route_id = $route_id AND ride_to_from = 0";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');

	$all = $result->fetch_assoc(); ?>
	<div class="stats clearfix to">
		<h3>To (<?php echo $all['count(ride_secs)']; ?> rides)</h3>
		<div class="the-stats">
			<div><div>best time</div> <div><?php outputTime($all['MIN(ride_secs)']); ?></div></div>
			<div><div>worst time</div> <div><?php outputTime($all['MAX(ride_secs)']); ?></div></div>
			<div><div>average time</div> <div><?php outputTime($all['AVG(ride_secs)']); ?></div></div>
		</div>
	</div>


<?php
	// from
	$sql = "SELECT count(ride_secs), MIN(ride_secs), MAX(ride_secs), AVG(ride_secs)
			FROM rides WHERE route_id = $route_id AND ride_to_from = 1";
	if(!$result = $db->query($sql))
		die('error with the database query - [' . $db->error . ']');

	$all = $result->fetch_assoc(); ?>
	<div class="stats clearfix from">
		<h3>From (<?php echo $all['count(ride_secs)']; ?> rides)</h3>
		<div class="the-stats">
			<div><div>best time</div> <div><?php outputTime($all['MIN(ride_secs)']); ?></div></div>
			<div><div>worst time</div> <div><?php outputTime($all['MAX(ride_secs)']); ?></div></div>
			<div><div>average time</div> <div><?php outputTime($all['AVG(ride_secs)']); ?></div></div>
		</div>
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