<?php 
$allowed_user_types = array(
        "GS"
 );
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>

<html>
<head><title>View Student Holds</title></head>
<link rel="stylesheet" href="style.css">
<body>

<?php

echo "<h1>View Student Holds</h1>";

// Get all students / faculty advisors 
$advisee_query = "SELECT P1.firstname AS studentfirstname, P1.lastname AS studentlastname, P2.firstname AS facultyfirstname, P2.lastname AS facultylastname, advises.studentid, advises.facultyid, advises.hold, advises.degreename FROM personalinfo AS P1, personalinfo AS P2, advises 
WHERE P1.id = advises.studentid AND P2.id = advises.facultyid;"; 
$advisee_result = mysqli_query($connection, $advisee_query);




echo "<table>
<tr><th>Advisee</th>
<th>Student ID</th>
<th>Faculty Advisor</th>
<th>Faculty ID</th>
<th>Hold</th>
</tr>";

// Displays a table of all students along with actions that can be taken
while ($row = mysqli_fetch_assoc($advisee_result)){
echo "<tr><td>".$row['studentfirstname']." ".$row['studentlastname']."</td>
<td>".$row['studentid']."</td>
<td>".$row['facultyfirstname']." ".$row['facultylastname']."</td>
<td>".$row['facultyid']."</td>
<td>";
if(ISSET($row['hold'])){
	echo $row['hold'];
}
else{ 
	echo "None";
}
echo "</td>
</tr>";
}
echo "</table>";
?>


</body>
</html>