<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Reset</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php include 'header-public.php'; ?>

<?php
include 'db-connect.php';

session_start();
session_destroy();

$file = fopen($script, "r");
$fileText = fread($file, filesize($script));

$fileTextSections = explode("---", $fileText);

$queryArray = explode(';', $fileTextSections[0]);
foreach ($queryArray as $query) {
	if (strpos($query, "DELIMITER CHANGE BELOW") !== false) {
		break;
	}
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "$query: ";
		echo mysqli_error($connection);
		goto done;
	}
}

$queryArray = explode('$$', $fileTextSections[2]);
foreach ($queryArray as $query) {
	if (strpos($query, "STOP") !== false) {
		echo "Database reset successfully";
		goto done;
	} else if (strpos($query, "delimiter") !== false) {
		continue;
	}
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "$query: ";
		echo mysqli_error($connection);
		goto done;
	}
}

done:

?>

<br/><br/>
<a href=login.php>Login</a>

</body>
</html>