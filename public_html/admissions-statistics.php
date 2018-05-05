<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Admissions Statistics</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array(
    "GS",
    "ADMIN"
);
include 'header.php';
?>

<h1>Admissions Statistics</h1>

<form method="POST" id="search">
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
    <input type="submit" name="filter" value="Filter">
    <input type="submit" name="viewall" value="View All">
</form>

<?php
include 'db-connect.php';
// Process filters:
$queryfilters = "";
if (isset($_POST['degree']) && $_POST['degree'] != "") {
	$degree = $_POST['degree'];
	$queryfilters = $queryfilters . " AND degreeapplyingfor = '$degree'";
}
if (isset($_POST['semester']) && $_POST['semester'] != "") {
	$semester = $_POST['semester'];
	$queryfilters = $queryfilters . " AND semester = '$semester'";
}
if (isset($_POST['year']) && $_POST['year'] > 0) {
	$year = $_POST['year'];
	$queryfilters = $queryfilters . " AND year = $year";
}

$query = "SELECT COUNT(*), AVG(gretotal), AVG(bachgpa)
	FROM admissionsapplication, academicinfo, applicantpersonalinfo
	WHERE admissionsapplication.id = academicinfo.applicationid AND admissionsapplication.id = applicantpersonalinfo.id AND finaldecision >= 3";
$query = $query . $queryfilters;
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$admittedcount = $row["COUNT(*)"];
	$admittedgreaverage = $row["AVG(gretotal)"];
	$admittedgpaaverage = $row["AVG(bachgpa)"];
} else {
	$admittedcount = 0;
	$admittedgreaverage = "";
	$admittedgpaaverage = "";
}

$query = "SELECT COUNT(*), AVG(gretotal), AVG(bachgpa)
	FROM admissionsapplication, academicinfo, applicantpersonalinfo
	WHERE admissionsapplication.id = academicinfo.applicationid AND admissionsapplication.id = applicantpersonalinfo.id AND finaldecision = 1";
$query = $query . $queryfilters;
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$rejectedcount = $row["COUNT(*)"];
	$rejectedgreaverage = $row["AVG(gretotal)"];
	$rejectedgpaaverage = $row["AVG(bachgpa)"];
} else {
	$rejectedcount = 0;
	$rejectedgreaverage = "";
	$rejectedgpaaverage = "";
}

$query = "SELECT COUNT(*), AVG(gretotal), AVG(bachgpa)
	FROM admissionsapplication, academicinfo, applicantpersonalinfo
	WHERE admissionsapplication.id = academicinfo.applicationid AND admissionsapplication.id = applicantpersonalinfo.id";
$query = $query . $queryfilters;
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$allcount = $row["COUNT(*)"];
	$allgreaverage = $row["AVG(gretotal)"];
	$allgpaaverage = $row["AVG(bachgpa)"];
} else {
	$allcount = 0;
	$allgreaverage = "";
	$allgpaaverage = "";
}

echo "<table>
	<tr>
	<th></th>
	<th>Admitted</th>
	<th>Rejected</th>
	<th>All</th>
	</tr>
	<tr>
	<td>Applicants</td>
	<td>$admittedcount</td>
	<td>$rejectedcount</td>
	<td>$allcount</td>
	</tr>
	<tr>
	<td>Average GRE Score</td>
	<td>$admittedgreaverage</td>
	<td>$rejectedgreaverage</td>
	<td>$allgreaverage</td>
	</tr>
	<tr>
	<td>Average Bachelor's GPA</td>
	<td>$admittedgpaaverage</td>
	<td>$rejectedgpaaverage</td>
	<td>$allgpaaverage</td>
	</tr>";
echo "</table>";
?>

</body>
</html>
