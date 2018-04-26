<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>

<html>
<head><title>Advisee Information</title></head>
<body>
<b>Advisee Information</b><br>

<?php

echo "<br><h2>Advisee Information</h2>";


$advisee_query = "SELECT firstname,lastname,studentid, hold, degree_name FROM personalinfo,advises WHERE advises.facultyid='$id' AND personalinfo.id=advises.studentid;";

$advisee_result = mysqli_query($conn, $advisee_query);

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
	<td>".$row['hold']."</td>
	<td>".$row['degree_name']."</td>
	</tr>";
}
echo "</table>";


?>


</body>
</html>
