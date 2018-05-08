<?php 
$allowed_user_types = array(
        "ADVISOR"
 );
include 'header.php';
include 'db-connect.php';
$facultyid = $_SESSION["id"];
?>

<html>
<head><title>View Advisee Information</title></head>
<link rel="stylesheet" href="style.css">
<body>

<?php

	echo "<h1>View Advisee Information</h1>";


	$advisee_check_query = "SELECT firstname,lastname,studentid, hold, degreename FROM personalinfo,advises WHERE advises.facultyid=$facultyid AND personalinfo.id=advises.studentid;";



	$advisee_check_result = mysqli_query($connection, $advisee_check_query);

	$row = mysqli_fetch_assoc($advisee_check_result);
	if(ISSET($row['studentid'])){

		echo "<table>
		<tr>
		<th>Name</th>
		<th>Student ID</th>
		<th>Hold</th>
		<th>Degree Name</th>
		</tr>";
		$advisee_query = "SELECT firstname,lastname,studentid, hold, degreename FROM personalinfo,advises WHERE advises.facultyid=$facultyid AND personalinfo.id=advises.studentid;";

		$advisee_result = mysqli_query($connection, $advisee_query);

		while($row = mysqli_fetch_assoc($advisee_result)){
			echo "<tr>
			<td>".$row['firstname']." ".$row['lastname']."</td>
			<td>".$row['studentid']."</td>
			<td>";
			if(ISSET($row['hold'])){
				echo $row['hold'];
			}
			else{ 
				echo "None";
			}
			echo "</td>
			<td>".$row['degreename']."</td>
			</tr>";
		}
		echo "</table>";
	}
	else {
		echo "You are not currently assigned as an advisor to any student.";
	}

?>


</body>
</html>
