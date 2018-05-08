<?php 
$allowed_user_types = array(
        "STUDENT"
 );
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>

<html>
<head><title>View Degree Requirements</title></head>
<link rel="stylesheet" href="style.css">
<body><h1>Degree Requirements</h1>


<?php
	$degreenamequery = "SELECT degreename FROM advises WHERE studentid=$id;";


	$degreenameresult = mysqli_query($connection, $degreenamequery);


	$row = mysqli_fetch_assoc($degreenameresult);
	$degreename = $row['degreename'];
	echo "<h2>Degree Name: ".$degreename."</h2>";

	$degreerequirementsquery = "SELECT degreerequirements.courseid,dept,coursenum,title FROM degreerequirements, courses WHERE degreerequirements.degreename = '$degreename' AND courses.courseid = degreerequirements.courseid;";
	$degreerequirementsresult = mysqli_query($connection, $degreerequirementsquery);

	echo "<h3>Core Courses: </h3>
	<table>
	<tr>
	<th>Course ID</th>
	<th>Department</th>
	<th>Course Num</th>
	<th>Title</th>
	</tr>";
	while($row = mysqli_fetch_assoc($degreerequirementsresult)){
		echo "<tr>
		<td>".$row['courseid']."</td>
		<td>".$row['dept']."</td>
		<td>".$row['coursenum']."</td>
		<td>".$row['title']."</td>
		</tr>";
	}

	echo "</table>
	<br>
	<b>Graduation Requirements:</b>
	<ul>
		<li>You must have taken all of the core courses listed above.</li>
		<li>You must have taken at least 10 courses.</li>
		<li>You must have completed a minimum of 30 credit hours.</li>
		<li>You must have a GPA of 3.0 or above.</li>
		<li>You must have no more than two letter grades below B-.</li>
	</ul>


	";




?>


</body>
</html>