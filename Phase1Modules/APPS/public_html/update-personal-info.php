<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Manage Users </title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("System Administrator");
include 'header.php';
?>
<h1>Update Personal Information</h1>
<form method="POST" id="idsearch">
	<h3>Search:</h3>
    Application ID: <input type="number" min=1 max=2147483647 name="id">
    <input type="submit" name="idsubmit" value="Search by ID">
    Last Name: <input type="text" max=30 name="lastname">
    <input type="submit" name="namesubmit" value="Search by Name">
    <input type="submit" name="viewall" value="View All">
</form>
<h3>Results:</h3>
<?php
include 'db-connect.php';
if (!isset($_POST['viewall']) && isset($_POST['idsubmit']) && $_POST['id'] > 0) {
	$id = mysqli_real_escape_string($connection, trim($_POST['id']));
	$query = "SELECT * FROM personalinfo WHERE applicationid = $id";
} else if (!isset($_POST['viewall']) && isset($_POST['namesubmit']) && isset($_POST['lastname'])) {
	$name = $_POST['lastname'];
	$query = "SELECT * FROM personalinfo WHERE lastname LIKE '%$name%'";
} else {
	$query = "SELECT * FROM personalinfo";
}
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
		echo "<td>{$row["applicationid"]}<input type=\"hidden\" name=\"id\" value=\"{$row["applicationid"]}\"></td>";
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
	$updatequery = "UPDATE personalinfo
		SET firstname='$firstname', lastname='$lastname', dob='$dob', address='$address', ssn='$ssn'
		WHERE applicationid=$id";
	$updateresult = mysqli_query($connection, $updatequery);
	if ($updateresult) {
		header("refresh:0");
	} else {
		echo "<script type='text/javascript'>alert('Data could not be updated');</script>";
	}
}
?>

</body>
</html>
