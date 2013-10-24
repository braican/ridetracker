
<?php 

function dbconnect () {
    // enter database credentials
	$db = new mysqli("localhost", "DATABASE_USER", "DATABASE_PWD", "DATABASE_NAME");
	if ($db->connect_errno) {
	    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}
	return $db;
}

function dbclose ($result, $db) {
	if($result && is_object($result)) $result->free();

	if($db) $db->close();
}

?>
