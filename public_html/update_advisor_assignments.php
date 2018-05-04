<?php include 'header.php';?>
<?php include 'db-connect.php';?>

<html>
<head><title>View / Edit Faculty Advisor</title></head>
<link rel="stylesheet" href="style.css">
<body>
<h1>View / Edit Faculty Advisor</h1>
<?php 
$id = $_SESSION["id"];



$query = "SELECT P1.firstname AS studentfirstname, P1.lastname AS studentlastname, P1.id AS studentid, hold, degreename, P2.firstname AS advisorfirstname, P2.firstname AS advisorlastname, P2.id AS advisorid FROM personalinfo AS P1, personalinfo AS P2, roles AS R1, roles AS R2,  advises WHERE R1.role='STUDENT' AND R1.id = P1.id AND advises.studentid = P1.id AND P2.id = advises.facultyid AND R2.role = 'ADVISOR' AND R2.id = advises.facultyid;";

$result = mysqli_query($connection, $query);



echo "<table>
<tr>
<th>Name</th>
<th>Student ID</th>
<th>Hold</th>
<th>Degree Name</th>
<th>Faculty Advisor</th>
<th>Faculty ID</th>
<th>&nbsp;&nbsp;&nbsp;Assign</th>";

while($row = mysqli_fetch_assoc($result)){
	
	echo "<tr>
	<td>".$row['studentfirstname']." ".$row['studentlastname']."</td>
	<td>".$row['studentid']."</td>
	<td>".$row['hold']."</td>
	<td>".$row['degreename']."</td>
	<td>".$row['advisorfirstname']." ".$row['advisorlastname']."</td>
	<td>".$row['advisorid']."</td>
	<td><form method='post' action='advisor_assign_submit.php'>
	<select name ='facultyid'>";

	$facultyquery = "SELECT firstname AS facultyfirstname,lastname AS facultylastname,personalinfo.id AS facultyid FROM personalinfo,roles WHERE personalinfo.id = roles.id AND roles.role='ADVISOR';";
	$facultyresult = mysqli_query($connection,$facultyquery);

	while($row1 = mysqli_fetch_assoc($facultyresult)){

		echo "<option value ='".$row1['facultyid']."' name='facultyid'>".$row1['facultyfirstname']." ".$row1['facultylastname']."</option>
		</select>
		<input type='submit' value='Assign'>
		<input type='hidden' name='studentid' value ='".$row['studentid']."'>
		</form></td>";
	}
		echo "</tr>
		</table>";
}

?>



</body>
</html>