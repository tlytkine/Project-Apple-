<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Upload Letter of Recommendation</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>

<h1>Upload Letter of Recommendation</h1>

<?php
include 'db-connect.php';
if (isset($_GET['recid'])) {
	$recid = $_GET['recid'];
	$query = "SELECT firstname, lastname, applicationid
		FROM applicantpersonalinfo, recommendation
		WHERE id = applicationid AND recommendationid = $recid";
	$result = mysqli_query($connection, $query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$name = $row['firstname'] . " " . $row['lastname'];
		$applicationid = $row['applicationid'];
		echo "<b>Student:</b> " . $name . "<br /><br />";
	} else {
		echo "Invalid recommendation link<br />";
		exit();
	}
}
?>

<form method="POST" id="upload" enctype="multipart/form-data">
	<b>File:</b> <input type="file" name="letter" accept=".pdf">
    <input type="submit" name="upload" value="Upload">
</form>

<?php
if (isset($_POST['upload']) && $_FILES['letter']['size'] > 0) {
	$data = mysqli_real_escape_string($connection, file_get_contents($_FILES['letter']['tmp_name']));
	$query = "UPDATE recommendation
		SET letterfile = '{$data}'
		WHERE recommendationid = {$_GET['recid']}";
	$result = mysqli_query($connection, $query);
	if (mysqli_affected_rows($connection) == 1) {
		echo "Recommendation uploaded";
		// Check if all letters have been uploaded:
		$query = "SELECT SUM(ISNULL(letterfile))
			FROM recommendation
			WHERE applicationid = $applicationid";
		$result = mysqli_query($connection, $query);
		if (mysqli_fetch_assoc($result)['SUM(ISNULL(letterfile))'] == 0) {
			// If all letters uploaded update document status:
			$query = "UPDATE documentstatus
			SET letterofrecrecieved = 1
			WHERE applicationid = $applicationid";
			$result = mysqli_query($connection, $query);
		}
	} else {
		echo "Recommendation could not be uploaded";
	}
}

?>

</body>
</html>
