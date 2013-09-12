<?php 

	require_once("db-util.php");
	$db = dbconnect();
	
	$sql = "SELECT * FROM routes";
	$result = $db->query($sql);
	if(!$result || mysqli_num_rows($result) == 0){
		echo ('<div class="no-routes">You have no routes</div>');
	} else {
		while($row = $result->fetch_assoc()){
			$name = $row['route_name'];
			$route_id = $row['route_id'];	 ?>

			<div class="clearfix project">
				<a href="rides.php?route=<?php echo $route_id ?>"><?php echo $name ?></a>
			</div>
<?php
		}
	}
	
	dbclose($result, $db);
?>