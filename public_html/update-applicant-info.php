<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Update Personal Information</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("System Administrator");
include 'header.php';
?>
<h1>Update Personal Information</h1>
<?php
include 'db-connect.php';
$query = "SELECT * FROM applicantpersonalinfo WHERE id = $_SESSION[id]";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
	echo "<table>
		<tr>
		<th>ID</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Date of Birth</th>
		<th>Address</th>
		<th>SSN</th>
		<th></th>
		</tr>";
	while ($row = mysqli_fetch_array($result)) {
		echo "<tr><form method=\"POST\">";
		echo "<td>{$row["id"]}<input type=\"hidden\" name=\"id\" value=\"{$row["id"]}\"></td>";
		echo "<td><input type=\"text\" id=\"firstname\" name=\"firstname\" maxlength=\"30\" required value='{$row["firstname"]}'></td>";
		echo "<td><input type=\"text\" id=\"lastname\" name=\"lastname\" maxlength=\"30\" required value='{$row["lastname"]}'></td>";
		echo "<td><input type=\"date\" id=\"dob\" name=\"dob\" min=\"1000-01-01\" max=\"3000-12-31\" value='{$row["dob"]}' required></td>";
		echo "<td><input type=\"text\" id=\"address\" name=\"address\" maxlength=\"100\" required value='{$row["address"]}' size=\"40\" /></td>";
		echo "<td><input id=\"ssn\" name=\"ssn\" pattern=\"^\d{3}-\d{2}-\d{4}$\" required value='{$row["ssn"]}' size=\"10\"></td>";
		echo "<td><button type=\"submit\" name=\"update\">Update</button></td>";
		echo "</form></tr>";
	}
	echo "</table><br/>";
} else {
	echo "No results<br />";
}

if (isset($_POST['update'])) {
	$id = $_POST['id'];
	$firstname = mysqli_real_escape_string($connection, trim($_POST['firstname']));
	$lastname = mysqli_real_escape_string($connection, trim($_POST['lastname']));
	$dob = mysqli_real_escape_string($connection, trim($_POST['dob']));
	$address = mysqli_real_escape_string($connection, trim($_POST['address']));
	$ssn = mysqli_real_escape_string($connection, trim($_POST['ssn']));
	$updatequery = "UPDATE applicantpersonalinfo
		SET firstname='$firstname', lastname='$lastname', dob='$dob', address='$address', ssn='$ssn'
		WHERE id=$id";
	$updateresult = mysqli_query($connection, $updatequery);
	if ($updateresult) {
		$query = "UPDATE documentstatus 
		SET personalinfosubmitted = TRUE
		WHERE applicationid = '$id'";
		$result  = mysqli_query($connection, $query);
		header("refresh:0");
	} else {
		echo "<script type='text/javascript'>alert('Data could not be updated');</script>";
	}
}
?>

</body>
</html>
