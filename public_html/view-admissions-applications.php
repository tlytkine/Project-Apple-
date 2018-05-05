

<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Admissions Applications and Reviews</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array(
    "CAC",
    "Grad Secretary",
    "System Administrator"
);
include 'header.php';
?>

<h1>Admissions Applications and Reviews</h1>

<form method="POST" id="search">
    Application ID: <input type="number" min=1 max=2147483647 name="id">
    Last Name: <input type="text" max=30 name="lastname">
	<br />
	Degree Type:
	<select name="degree">
		<option value="" selected></option>
		<option value="M.S.">M.S.</option>
		<option value="Ph.D.">Ph.D.</option>
		<option value="Direct Ph.D.">Direct Ph.D.</option>       
	</select>
	Semester:
	<select name="semester">
		<option value="" selected></option>
		<option value="Fall">Fall</option>
		<option value="Spring">Spring</option>
	</select>
	Year: <input type="number" min=1000 max=9999 name="year">
	Admitted: <input type="checkbox" name="admitted">
    <input type="submit" name="filter" value="Filter">
    <input type="submit" name="viewall" value="View All">
</form>

<?php
include 'db-connect.php';
// Search for an application
$query = "SELECT *
	FROM admissionsapplication, academicinfo, applicantpersonalinfo
	WHERE admissionsapplication.id = academicinfo.applicationid AND admissionsapplication.id = applicantpersonalinfo.id";

if (!isset($_POST['viewall']) && $_POST['id'] > 0) {
	$id = mysqli_real_escape_string($connection, trim($_POST['id']));
	$query = $query . " AND applicantpersonalinfo.id = $id";
}
if (!isset($_POST['viewall']) && isset($_POST['lastname'])) {
	$name = $_POST['lastname'];
	$query = $query . " AND lastname LIKE '%$name%'";
}
if (!isset($_POST['viewall']) && $_POST['degree'] != "") {
	$degree = $_POST['degree'];
	$query = $query . " AND degreeapplyingfor = '$degree'";
}
if (!isset($_POST['viewall']) && $_POST['semester'] != "") {
	$semester = $_POST['semester'];
	$query = $query . " AND semester = '$semester'";
}
if (!isset($_POST['viewall']) && $_POST['year'] > 0) {
	$year = $_POST['year'];
	$query = $query . " AND year = $year";
}
if (!isset($_POST['viewall']) && isset($_POST['admitted'])) {
	$query = $query . " AND finaldecision >= 3";
}

$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
	echo "<table>
		<tr>
		<th>ID</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Semester</th>
		<th>Year</th>
		<th>Degree Type</th>
		<th>Status</th>
		<th>Final Decision</th>
		<th>Options</th>
		</tr>";
    while ($row = mysqli_fetch_assoc($result)) {
		$decision = "";
		if ($row["finaldecision"] == 1) {
			$decision = "Reject";
		} else if ($row["finaldecision"] == 3) {
			$decision = "Admit without Aid";
		} else if ($row["finaldecision"] == 4) {
			$decision = "Admit with Aid";
		}
		echo "<tr>";
		echo "<td>{$row["id"]}</td>";
		echo "<td>{$row["firstname"]}</td>";
		echo "<td>{$row["lastname"]}</td>";
		echo "<td>{$row["semester"]}</td>";
		echo "<td>{$row["year"]}</td>";
		echo "<td>{$row["degreeapplyingfor"]}</td>";
		echo "<td>{$row["status"]}</td>";
		echo "<td>$decision</td>";
        echo "<td><a href=\"view-application.php?viewapplication=" . $row['id'] . "\">Application</a> ";
        echo "<a href=\"view-review.php?view=" . $row['id'] . "\">Review</a></td>";
        echo "</tr>";
    }
	echo "</table>";
} else {
    echo "No data";
}
?>

</body>
</html>
