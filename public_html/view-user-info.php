<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>View Users</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("GS");
include 'header.php';
?>
<h1>View User Information</h1>
<form method="POST" id="idsearch">
	<h3>Search:</h3>
    ID: <input type="number" min=1 max=2147483647 name="id">
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
	$query = "SELECT * FROM personalinfo p, users u WHERE u.id = $id AND p.id = u.id";
} else if (!isset($_POST['viewall']) && isset($_POST['namesubmit']) && isset($_POST['lastname'])) {
	$name = $_POST['lastname'];
	$query = "SELECT * FROM personalinfo p, users u WHERE p.id = u.id AND lastname LIKE '%$name%'";
} else {
	$query = "SELECT * FROM personalinfo p, users u WHERE p.id = u.id";
}
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
	echo '<table>
		<tr>
		<th>ID</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Date of Birth</th>
		<th>Address</th>
		<th>Email</th>
		</tr>';
	while ($row = mysqli_fetch_array($result)) {
		echo "<tr>";
		echo "<td>{$row["id"]}</td>";
		echo "<td>{$row["firstname"]}</td>";
		echo "<td>{$row["lastname"]}</td>";
		echo "<td>{$row["dob"]}</td>";
		echo "<td>{$row["address"]}</td>";
		echo "<td>{$row["email"]}</td>";
		echo "</tr>";
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
		WHERE id=$id";
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
