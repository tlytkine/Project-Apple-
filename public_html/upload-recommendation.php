<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Upload Letter of Recommendation</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>

<h1>Upload Letter of Recommendation</h1>

<form method="POST" id="upload" enctype="multipart/form-data">
	File: <input type="file" name="letter" accept=".pdf">
    <input type="submit" name="upload" value="Upload">
</form>

<?php
include 'db-connect.php';
if (isset($_GET['recid'])) {
	echo "Recommendation ID: " . $_GET['recid'] . "<br />";
} else {
	echo "No recommendation ID<br />";
}

if (isset($_POST['upload']) && $_FILES['letter']['size'] > 0) {
	$data = mysqli_real_escape_string($connection, file_get_contents($_FILES['letter']['tmp_name']));
	$query = "UPDATE recommendation
		SET letterfile = '{$data}'
		WHERE recommendationid = {$_GET['recid']}";
	$result = mysqli_query($connection, $query);
	if (mysqli_affected_rows($connection) == 1) {
		echo "Recommendation uploaded";
	} else {
		echo "Recommendation could not be uploaded";
	}
}

?>

</body>
</html>
