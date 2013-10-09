<?php include('inc/header.html'); ?>

	<header class="page-header">
		<div class="container clearfix">
			<h1>Ridetracker</h1>
		</div>
	</header>

	<div class="main-content">
		<section class="route-list refresh-this container">
			<?php include("util/get-routes.php"); ?>
		</section><!-- .route-list -->
	
		<section class="container">
			<form action="util/add-route.php" method="post" data-refresh="util/get-routes.php">
				<input type="text" name="new_route" maxlength="70" placeholder="add route"></input>
				<input type="number" step="any" name="length" placeholder="length (miles)"></input>
				<input type="submit" value="Submit"></input>
			</form>
		</section>

		<div class="ajax-message"></div>
	</div>

<?php include('inc/footer.html'); ?>