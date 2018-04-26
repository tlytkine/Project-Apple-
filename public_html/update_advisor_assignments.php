<?php include 'header.php';?>
<?php include 'db-connect.php';?>

<html>
<head><title>View / Edit Faculty Advisor</title></head>
<link rel="stylesheet" href="style.css">
<body>
<h1>View / Edit Faculty Advisor</h1>
<?php 
$id = $_SESSION["id"];


$advises_query = "SELECT P1.firstname AS studentfirstname, P1.lastname AS studentlastname, advises.studentid, advises.hold, advises.degreename,
P2.firstname AS facultyfirstname, P2.lastname AS facultylastname, advises.facultyid FROM advises, roles AS R1, roles AS R2, personalinfo AS P1, personalinfo AS P2 WHERE R1.id = advises.studentid AND R1.role='STUDENT' AND R2.id = advises.facultyid AND R2.role='ADVISOR' AND P1.id=advises.studentid AND P2.id=advises.facultyid;";
$advises_result = mysqli_query($connection, $advises_query);



$faculty_query = "SELECT firstname, lastname, role FROM personalinfo, roles WHERE personalinfo.id = roles.id AND roles.role = 'ADVISOR';";

$faculty_result = mysqli_query($connection, $faculty_query);


echo "<table>
<tr>
<th>Name</th>
<th>Student ID</th>
<th>Hold</th>
<th>Degree Name</th>
<th>Faculty Advisor</th>
<th>Faculty ID</th>
<th>&nbsp;&nbsp;&nbsp;Assign</th>";

while($row = mysqli_fetch_assoc($advises_result)){
	
	echo "<tr>
	<td>".$row['studentfirstname']." ".$row['studentlastname']."</td>
	<td>".$row['studentid']."</td>
	<td>".$row['hold']."</td>
	<td>".$row['degreename']."</td>
	<td>".$row['facultyfirstname']." ".$row['facultylastname']."</td>
	<td>".$row['facultyid']."</td>
	<td><form method='post' action='advisor_assign_submit.php'>
	<select name ='facultyid'>";
	while($row1 = mysqli_fetch_assoc($faculty_result)){
		echo "<option value ='".$row1['facultyid']."'>".$row1['firstname']." ".$row1['lastname']."</option>";
	}
	 echo "</select>
	<input type='submit' value='Assign'>
	<input type='hidden' name='gwid' value ='".$row['studentid']."'>
	</form></td>
	</tr>
	</table>";
 
}

?>



</body>
</html>