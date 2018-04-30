<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>


<html>
<head><title>List of Advisees</title></head>
<link rel="stylesheet" href="style.css">
<body>

<?php

echo "<br><h2>List of Advisees</h2>";


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
	<td>".$row['hold']."</td>
	<td>".$row['degreename']."</td>
	</tr>";
}
echo "</table>";


?>


</body>
</html>