<?php
	$servername = "localhost";
	$serverusername = "root";
	$serverpassword = "";
	$dbname = "phase1";
	$script = "../../mysql/bin/tables.sql";
	$connection = mysqli_connect($servername, $serverusername, $serverpassword, $dbname);
	if (mysqli_connect_errno()) {
		echo "Database connection error: " . mysqli_connect_error();
	}
?>
