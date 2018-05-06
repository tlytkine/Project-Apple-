<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>View All Applications</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("REVIEWER", "CAC");
include 'header.php';

?>

<h1>Completed Applications</h1>

<form method="POST" id="search">
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

$query = "SELECT * FROM admissionsapplication, academicinfo, applicantpersonalinfo
WHERE admissionsapplication.id=academicinfo.applicationid AND admissionsapplication.id=applicantpersonalinfo.id AND status='Complete'";

if (!isset($_POST['viewall']) && isset($_POST['idsubmit']) && $_POST['id'] > 0) {
    $id = mysqli_real_escape_string($connection, trim($_POST['id']));
	$query = $query . " AND id = $id";
} else if (!isset($_POST['viewall']) && isset($_POST['namesubmit']) && isset($_POST['lastname'])) {
	$name = mysqli_real_escape_string($connection, trim($_POST['lastname']));
	$query = $query . " AND lastname LIKE '%$name%'";
}

$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
	echo "<table>
		<tr>
		<th>ID</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Degree Type</th>
		<th>Options</th>
		</tr>";
	while ($row = mysqli_fetch_array($result)) {
		echo "<tr>";
		echo "<td>{$row["id"]}</td>";
		echo "<td>{$row["firstname"]}</td>";
		echo "<td>{$row["lastname"]}</td>";
		echo "<td>{$row["degreeapplyingfor"]}</td>";
		echo "<td><a href=\"review-form.php?review={$row["id"]}\">Review</a></td>";
		echo "</tr>";
	}
	echo "</table><br/>";
} else {
	echo "No complete applications<br/>";
}

?>



