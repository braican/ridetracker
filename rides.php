<?php include('inc/header.html'); ?>
<?php
	require_once('util/db-util.php');
	$db = dbconnect();
	$route = $_GET['route'];

	$sql = "SELECT route_name FROM routes WHERE route_id = $route";
	if(!$result = $db->query($sql))
		die('error connecting - [' . $db->error . ']');

	while($row = $result->fetch_assoc()){
		$title = $row['route_name'];
	}
?>

	<header class="page-header">
		<div class="container clearfix">
			<a href="index.php">&#10094;&#10094; Routes</a>	
			<h1><?php echo $title; ?></h1>
		</div>
		<div class="rides-headers">
			<div class="container">
				<div class="ride">
					<div class="date">Date</div>
					<div class="time">Time</div>
					<div class="to-from">To/From</div>
				</div>
			</div>
		</div>
	</header>
	
	<div class="main-content">

		<section class="ride-list refresh-this clearfix container">
			<?php include("util/get-rides.php"); ?>
		</section><!-- .ride-list -->
		
		<section class="container">
			<form action="util/add-ride.php" method="post" data-refresh="util/get-rides.php?route=<?php echo $route; ?>">
				<input type="date" name="ride_date" value="<?php echo date('Y-m-d'); ?>"></input>
				
				<div>
					<input type="radio" value="0" id="ride_to" name="ride_to_from"><label for="ride_to">To</label>
					<input type="radio" value="1" id="ride_from" name="ride_to_from"><label for="ride_from">From</label>
				</div>
				
				<fieldset>
					<legend>time</legend>
					<input type="number" name="ride_hrs" placeholder="hrs">
					<input type="number" name="ride_mins" max="59" placeholder="min">
					<input type="number" name="ride_secs" max="59" placeholder="sec">
				</fieldset>
				
				<input type="hidden" name="route_id" value="<?php echo $route; ?>">
				<input type="submit" value="Submit"></input>
			</form>
		</section>
		
		<section class="the-charts">
			<canvas id="canvas" height="450" width="600"></canvas>
		</section>
	</div><!-- .main-content -->


	<?php dbclose($result, $db); ?>

<?php include('inc/footer.html'); ?>