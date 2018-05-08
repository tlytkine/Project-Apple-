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
<body><h1>View Degree Requirements</h1>


<?php
	$degreenamequery = "SELECT degreename FROM advises WHERE studentid=$id;";


	$degreenameresult = mysqli_query($connection, $degreenamequery);

	$row = mysqli_fetch_assoc($degreenameresult);
	$degreename = $row['degreename'];

	echo "Degree Name: ";
	echo $degreename;
	echo "<br>";

	$degreerequirementsquery = "SELECT degreerequirements.courseid,dept,coursenum,title FROM degreerequirements, courses WHERE degreerequirements.degreename = '$degreename' AND courses.courseid = degreerequirements.courseid;";
	$degreerequirementsresult = mysqli_query($connection, $degreerequirementsquery);

	echo "<table>
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

	echo "</table>";




?>


</body>
</html>