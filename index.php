<?php include('inc/header.html'); ?>

	<h1>Rides</h1>
	<div class="ride-list refresh-this">
		<?php include("util/get-routes.php"); ?>
	</div><!-- .ride-list -->

	<form action="util/add-route.php" method="post" data-refresh="util/get-routes.php">
		<input type="text" name="new_route" maxlength="70" placeholder="add route"></input>
		<input type="number" step="any" name="length" placeholder="length (miles)"></input>
		<input type="submit" value="Submit"></input>
	</form>

	<div class="ajax-message"></div>

<?php include('inc/footer.html'); ?>