<?php 
$allowed_user_types = array(
        "STUDENT"
 );
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>

<html>
<head><title>Check Degree Status</title></head>
<link rel="stylesheet" href="style.css">
<body><h1>Check Degree Status</h1>


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

	echo "<h3>Core Course Status</h3>
	<table>
	<tr>
	<th>Course ID</th>
	<th>Department</th>
	<th>Course Num</th>
	<th>Title</th>
	<th>Requirement Satisfied?</th>
	</tr>";
	while($row = mysqli_fetch_assoc($degreerequirementsresult)){
		echo "<tr>
		<td>".$row['courseid']."</td>
		<td>".$row['dept']."</td>
		<td>".$row['coursenum']."</td>
		<td>".$row['title']."</td>
		<td>";
		$cid = $row['courseid'];
		$requirement_check_query = "SELECT studentid,grade FROM transcripts,courses WHERE transcripts.studentid = $id AND courses.courseid = $cid AND transcripts.dept = courses.dept AND courses.coursenum = transcripts.coursenum;";
		$requirement_check_result = mysqli_query($connection, $requirement_check_query);
		$row = mysqli_fetch_assoc($requirement_check_result);
		if(ISSET($row['grade'])){
			$grade = $row['grade'];
			if(strcmp($grade, 'IP')==0){
				echo "In progress";
			}
			else {
				echo "&#10004";

			}
		}
		else {
			echo "&#10008";
		}
		echo "</td>
		</tr>";
	}

	echo "</table>
	<br>
	<b>Graduation Requirements: </b>
	<table>
	<tr>
	<th>Requirement</th>
	<th>Status</th>
	<th>Satisfied?</th>
	</tr>
	<tr>
	<td>Core Courses</td>
	<td>Insert Status</td>
	<td>Insert checkmark or x</td>
	</tr>
	<tr>
	<td>Minimum of 10 courses</td>
	<td>Insert Status</td>
	<td>Insert checkmark or x</td>
	</tr>
	<tr>
	<td>Minimum of 10 courses</td>
	<td>Insert Status</td>
	<td>Insert checkmark or x</td>
	</tr>
	<tr>
	<td>Minimum of 30 credit hours</td>
	<td>Insert Status</td>
	<td>Insert checkmark or x</td>
	</tr>
	<td>Minimum GPA of 3.0</td>
	<td>Insert Status</td>
	<td>Insert checkmark or x</td>
	</tr>
	<td>No more than two letter grades below B-</td>
	<td>Insert Status</td>
	<td>Insert checkmark or x</td>
	</tr>
	</table>
	";





?>


</body>
</html>