<?php 
	$allowed_user_types = array(
        "GS",
        "ADMIN"
 	);
	include 'header.php';
	include 'db-connect.php';
	$id = $_SESSION["id"];
?>


<html>
<head><title>List of Advisees</title></head>
<link rel="stylesheet" href="style.css">
<body>

<?php

echo "<h1>List of Advisees</h1>";



$advisee_check_query = "SELECT firstname,lastname,studentid, hold, degreename FROM personalinfo,advises WHERE personalinfo.id=advises.studentid;";

$advisee_check_result = mysqli_query($connection, $advisee_query);


$row = mysqli_fetch_assoc($advisee_check_result);

if(ISSET($row['studentid'])){

	$advisee_query = "SELECT firstname,lastname,studentid, hold, degreename FROM personalinfo,advises WHERE personalinfo.id=advises.studentid;";

	$advisee_result = mysqli_query($connection, $advisee_query);

	echo "<table>
	<tr>
	<th>Name</th>
	<th>Student ID</th>
	<th>Hold</th>
	<th>Degree Name</th>
	</tr>";

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
	echo "There are no advisees currently present in the system.";
}


?>


</body>
</html>