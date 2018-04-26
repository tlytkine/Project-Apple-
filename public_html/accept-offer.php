<html lang="en-US">
<head>
  <meta charset="UTF-8">
  <title>Accept Admissions Offer</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("Applicant");
include 'header.php';
?>

<h1>Accept Admissions Offer</h1>
<form method="POST" id="acceptoffer">
	<input type="submit" name="accept" value="Accept">
</form>

<?php
include 'db-connect.php';

$id = $_SESSION['id'];

if (isset($_POST['accept'])) {
	$query = "INSERT INTO roles(id, role) VALUES ($id, 'STUDENT')";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Offer could not be accepted');</script>";
		exit();
	}
	$query = "DELETE FROM roles WHERE id=$id AND role='APPLICANT'";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Offer could not be accepted');</script>";
		exit();
	}
	
	// Reset roles session variable:
	$query = "SELECT role FROM roles WHERE id={$_SESSION['id']}";
	$result = mysqli_query($connection, $query);
	$roles = array();
	while ($row = mysqli_fetch_array($result)) {
		 $roles[] = $row["role"];
	}
	$_SESSION["roles"] = $roles;
	
	// Add hold:
	$query = "INSERT INTO advises (studentid, hold) VALUES ($id, 'New Student')";
	$result = mysqli_query($connection, $query);	
	if (!$result) {
		echo "<script type='text/javascript'>alert('Error adding hold');</script>";
		exit();
	}
	
	// Welcome message and redirect:
	echo "<script type='text/javascript'>alert('Congratulations and welcome to the George Washington University! Go Colonials!');</script>";
	header("refresh:0 url=menu.php");
}

?>

</body>
</html>