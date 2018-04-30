<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>


<html>
<head><title>Graduating Students</title></head>
<link rel="stylesheet" href="style.css">
<body>

<?php

echo "<br><h2>List of Graduating Students</h2>";


$graduating_students_query = "SELECT DISTINCT personalinfo.firstname,personalinfo.lastname,graduationapplications.studentid, graduationapplications.year,advises.degreename FROM personalinfo,graduationapplication,advises WHERE graduationapplication.studentid = personalinfo.id AND advises.studentid = graduationapplication.studentid AND graduationapplication.cleared=1;";

$graduating_students_result = mysqli_query($connection, $graduating_students_query);

echo "<table>
<tr>
<th>Name</th>
<th>Student ID</th>
<th>Year</th>
<th>Degree Name</th>
</tr>";

while($row = mysqli_fetch_assoc($graduating_students_result)){
	echo "<tr>
	<td>".$row['firstname']." ".$row['lastname']."</td>
	<td>".$row['studentid']."</td>
	<td>".$row['year']."</td>
	<td>".$row['degreename']."</td>
	</tr>";
}
echo "</table>";


?>


</body>
</html>