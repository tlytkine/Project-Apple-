<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSON["id"];
?>

<html>
<head><title>Update Student Holds</title></head>
<body>

<?php


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
<th>Lift Hold</th>
<th>Place Hold</th>
</tr>";

// Displays a table of all students along with actions that can be taken
while ($row = mysqli_fetch_assoc($advisee_result)){
echo "<tr><td>".$row['studentfirstname']." ".$row['studentlastname']."</td>
<td>".$row['studentid']."</td>
<td>".$row['facultyfirstname']." ".$row['facultylastname']."</td>
<td>".$row['facultyid']."</td>
<td>".$row['hold']."</td>
<form method ='post' action='hold_submit.php'>
<input type='hidden' name='studentid' value ='".$row['studentid']."'>
<input type='hidden' name='facultyid' value='".$row['facultyid']."'>
<td><input type='submit' name='lift' value='Lift Hold'></form></td>
<form method='post' action='hold_submit.php'>
<input type='hidden' name='studentid' value ='".$row['studentid']."'>
<input type='hidden' name='facultyid' value='".$row['facultyid']."'>
<td><input type='text' name='holdtext'>
<input type='submit' name='place' value='Place Hold'></td>
</form>
</tr>";
}
echo "</table>";
?>


</body>
</html>