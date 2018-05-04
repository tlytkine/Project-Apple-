<?php include 'header.php';?>
<?php include 'db-connect.php';?>

<html>
<head><title>Assign Faculty Advisor</title></head>
<link rel="stylesheet" href="style.css">
<body>
<h1>Assign New Faculty Advisor</h1>
<?php 
$id = $_SESSION["id"];



$assign = $_POST['assign'];

if($assign){

	$facultyid = $_POST['facultyid'];
	$studentid = $_POST['studentid'];

	$advisor_update = "UPDATE advises SET facultyid = '$facultyid' WHERE 
	studentid = '$studentid';";
	$advisor_result = mysqli_query($connection, $advisor_result);

	if($advisor_result){
		echo "Advisor sucessfully assigned!";
	}
	else {
		echo "Advisor was not able to be assigned.";
	}
}

$current_students = "SELECT firstname,lastname,personalinfo.id,degreename,hold FROM personalinfo, advises, roles WHERE personalinfo.id = roles.id AND roles.role = 'STUDENT' AND advises.studentid = roles.id AND advises.facultyid IS NULL;";
$current_students_result = mysqli_query($connection, $current_students);


echo "<table>
<tr>
<th>Name</th>
<th>Student ID</th>
<th>Hold</th>
<th>Degree Name</th>
<th>&nbsp;&nbsp;&nbsp;Assign</th>
</tr>";

while($row = mysqli_fetch_assoc($current_students_result)){
	
	echo "<tr>
	<td>".$row['firstname']." ".$row['lastname']."</td>
	<td>".$row['id']."</td>
	<td>".$row['hold']."</td>
	<td>".$row['degreename']."</td>
	<td><form method='post'>
	<select name ='facultyid'>";

	$facultyquery = "SELECT firstname AS facultyfirstname,lastname AS facultylastname,personalinfo.id AS facultyid FROM personalinfo,roles WHERE personalinfo.id = roles.id AND roles.role='ADVISOR';";
	$facultyresult = mysqli_query($connection,$facultyquery);

	while($row1 = mysqli_fetch_assoc($facultyresult)){

		echo "<option value ='".$row1['facultyid']."' name='facultyid'>".$row1['facultyfirstname']." ".$row1['facultylastname']."</option>
		</select>
		<input type='submit' value='Assign' name='assign'>
		<input type='hidden' name='studentid' value ='".$row['studentid']."'>
		</form></td>";
	}
	echo "</tr>";
}
echo "</table>";



?>



</body>
</html>