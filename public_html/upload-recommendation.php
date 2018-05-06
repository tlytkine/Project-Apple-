<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Upload Letter of Recommendation</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>

<h1>Upload Letter of Recommendation</h1>

<form method="POST" id="upload" enctype="multipart/form-data">
	Recommendation ID: <input type="number" name="recid">
	File: <input type="file" name="letter" accept=".pdf">
    <input type="submit" name="upload" value="Upload">
</form>

<?php
include 'db-connect.php';
if (isset($_POST['recid']) && $_FILES['letter']['size'] > 0) {
	$data = mysqli_real_escape_string($connection, file_get_contents($_FILES['letter']['tmp_name']));
	$recid = $_POST['recid'];
	$query = "UPDATE recommendation
		SET letterfile = '{$data}'
		WHERE recommendationid = {$recid}";
	$result = mysqli_query($connection, $query);
}

?>

</body>
</html>
