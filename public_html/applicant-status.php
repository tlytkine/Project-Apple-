<html lang="en-US">
<head>
  <meta charset="UTF-8">
  <title>Admissions Application Status</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("Applicant");
include 'header.php';
?>

<h1>Admissions Application Status</h1>

<?php

include 'db-connect.php';

// Look for application:
$application_lookup_query = "SELECT id, status, finaldecision
	FROM admissionsapplication
	WHERE id={$_SESSION["id"]}";
$query = $application_lookup_query;
$result = mysqli_query($connection, $query);
$rows = mysqli_num_rows($result);
// Check if application was found:
if ($rows != 1) {
	// If not, create new application:
	$query = "INSERT INTO admissionsapplication (id, status, finaldecision)
		VALUES ({$_SESSION["id"]}, 'Incomplete', 0)";
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
		VALUES ({$_SESSION["id"]}, FALSE, FALSE, FALSE)";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Error creating application');</script>";
	}

	// Add academicinfo row:
	$query = "INSERT INTO academicinfo(applicationid)
		VALUES ({$_SESSION["id"]})";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Error creating application');</script>";
	}
	
	// Add recommendation rows:
	$query = "INSERT INTO recommendation (applicationid) VALUES ('{$_SESSION['id']}')";
	$result = mysqli_query($connection, $query);

	$query = "INSERT INTO recommendation (applicationid) VALUES ('{$_SESSION['id']}')";
	$result = mysqli_query($connection, $query);

	$query = "INSERT INTO recommendation (applicationid) VALUES ('{$_SESSION['id']}')";
	$result = mysqli_query($connection, $query);
	
} else {
	// Get application ID:
	$row = mysqli_fetch_array($result);
	$_SESSION["id"] = $row["id"];
}
echo "ID: {$_SESSION["id"]}<br/>";
if ($row["finaldecision"] == 0) {
	if ($row["status"] == "Complete") {
		echo "Application Status: Complete and Under Review<br/>";
	} else {
		echo "Application Status: {$row["status"]}<br/>";
	}
	$query = "SELECT applicationsubmitted, transcriptrecieved, letterofrecrecieved, personalinfosubmitted
	FROM documentstatus
	WHERE applicationid={$_SESSION["id"]}";
	$statusresult = mysqli_query($connection, $query);
	$statusrow = mysqli_fetch_array($statusresult);
	$personalinfosubmitted = ($statusrow["personalinfosubmitted"]) ? "&#x2714; Submitted" : "&#x2718; Not Submitted";
	$applicationsubmitted = ($statusrow["applicationsubmitted"]) ? "&#x2714; Submitted" : "&#x2718; Not Submitted";
	$transcriptrecieved = ($statusrow["transcriptrecieved"]) ? "&#x2714; Received" : "&#x2718; Not Received";
	$letterofrecrecieved = ($statusrow["letterofrecrecieved"]) ? "&#x2714; Received" : "&#x2718; Not Received";
	echo "Personal Information: $personalinfosubmitted<br/>";
	echo "Application: $applicationsubmitted<br/>";
	echo "Transcript: $transcriptrecieved<br/>";
	echo "Letter of Recommendation: $letterofrecrecieved";
} else {
	echo "Application Status: Decision Available<br/>";
	if ($row["finaldecision"] == 1) {
		echo "Your application for admission has been denied.";
	} else if ($row["finaldecision"] == 3 || $row["finaldecision"] == 4) {
		echo "Congratulations you have been admitted. The formal letter of acceptance will be mailed. <br />";
		echo "<a href='accept-offer.php'>Accept Offer</a>";
	} else {
		echo "An error has occurred. Please contact the admissions office for assistance.";
	}
}
?>

</body>
</html>