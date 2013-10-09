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
		<div class="container clearfix title-bar">
			<a href="index.php">&#10094;&#10094; Routes</a>	
			<h1><?php echo $title; ?></h1>
		</div>
	</header>
	<div class="rides-headers">
		<div class="container">
			<div class="ride">
				<div class="date">Date</div>
				<div class="time">Time</div>
				<div class="to-from">To/From</div>
			</div>
		</div>
	</div>
	
	<div class="main-content">
	
		<h2><div class="container">Add new ride</div></h2>
		<section class="container">
			
			<form action="util/add-ride.php" method="post" data-refresh="util/get-rides.php?route=<?php echo $route; ?>" id="add-ride-form">
				<input type="date" name="ride_date" value="<?php echo date('Y-m-d'); ?>"></input>
				
				<span>
					<input type="radio" value="0" id="ride_to" name="ride_to_from"><label for="ride_to">To</label>
					<input type="radio" value="1" id="ride_from" name="ride_to_from"><label for="ride_from">From</label>
				</span>
				
				<fieldset class="the-time-fields clearfix">
					<legend>time</legend>
					<input type="number" name="ride_hrs" placeholder="hrs">
					<input type="number" name="ride_mins" max="59" placeholder="min">
					<input type="number" name="ride_secs" max="59" placeholder="sec">
				</fieldset>
				
				<input type="hidden" name="route_id" value="<?php echo $route; ?>">
				<input type="submit" value="Submit"></input>
			</form>
		</section>
		
		
		<h2><div class="container">Ride list</div></h2>
		<section class="ride-list refresh-this clearfix container">
			<?php include("util/get-rides.php"); ?>
		</section><!-- .ride-list -->

		<h2><div class="container">Graphics</div></h2>
		<section class="the-charts container">
			
			<div id="to-canvas-container">
				<h3>To</h3>
				<canvas id="to-canvas" height="450" width="527"></canvas>
			</div>

			<div id="from-canvas-container">
				<h3>From</h3>
				<canvas id="from-canvas" height="450" width="527"></canvas>
			</div>
		</section>
	</div><!-- .main-content -->


	<?php dbclose($result, $db); ?>

<?php include('inc/footer.html'); ?>