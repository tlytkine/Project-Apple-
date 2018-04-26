<?php
// Look for application:
$application_lookup_query = "SELECT id, status, finaldecision
	FROM admissionsapplication
	WHERE id=$id";
$query = $application_lookup_query;
$result = mysqli_query($connection, $query);
$rows = mysqli_num_rows($result);
// Check if application was found:
if ($rows != 1) {
	// If not, create new application:
	$query = "INSERT INTO admissionsapplication (id, status, finaldecision)
		VALUES ($id, 'Incomplete', 0)";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Application could not be created');</script>";
	}

	// Rerun query:
	$query = $application_lookup_query;
	$result = mysqli_query($connection, $query);
	$rows = mysqli_num_rows($result);
	$row = mysqli_fetch_array($result);

	// Add documentstatus row:
	$query = "INSERT INTO documentstatus(applicationid, applicationsubmitted, transcriptrecieved, letterofrecrecieved)
		VALUES ($id, FALSE, FALSE, FALSE)";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Error creating application');</script>";
	}

	// Add academicinfo row:
	$query = "INSERT INTO academicinfo(applicationid)
		VALUES ($id)";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Error creating application');</script>";
	}
	
	// Add recommendation rows:
	$query = "INSERT INTO recommendation (applicationid) VALUES ('$id')";
	$result = mysqli_query($connection, $query);

	$query = "INSERT INTO recommendation (applicationid) VALUES ('$id')";
	$result = mysqli_query($connection, $query);

	$query = "INSERT INTO recommendation (applicationid) VALUES ('$id')";
	$result = mysqli_query($connection, $query);
}
?>