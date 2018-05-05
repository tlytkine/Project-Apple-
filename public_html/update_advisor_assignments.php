<?php include 'header.php';?>
<?php include 'db-connect.php';?>

<html>
<head><title>View / Edit Faculty Advisor</title></head>
<link rel="stylesheet" href="style.css">
<body>
<h1>View / Edit Faculty Advisor</h1>
<?php 
$id = $_SESSION["id"];
$assign1 = $_POST['assign1'];
$assign2 = $_POST['assign2'];

if($assign1){


		$facultyidother = $_POST['facultyidother'];
		$studentidother = $_POST['studentidother'];
		$advisor_update = "UPDATE advises SET facultyid = $facultyidother WHERE 
		studentid = $studentidother;";
		$advisor_result = mysqli_query($connection, $advisor_update);

		$current_students_result = mysqli_query($connection, $current_students);
		$facultyresult = mysqli_query($connection,$facultyquery);
		$result = mysqli_query($connection, $query);

		if($advisor_result){
			echo "Advisor sucessfully assigned!";
		}
}

if($assign2){

		$facultyidnew = $_POST['facultyidnew'];
		$studentidnew = $_POST['studentidnew'];
		$advisor_update = "UPDATE advises SET facultyid = $facultyidnew WHERE 
		studentid = $studentidnew;";
		$advisor_result = mysqli_query($connection, $advisor_update);

		$current_students_result = mysqli_query($connection, $current_students);
		$facultyresult = mysqli_query($connection,$facultyquery);
		$result = mysqli_query($connection, $query);

		if($advisor_result){
			echo "Advisor sucessfully assigned!";
		}
}


$query = "SELECT P1.firstname AS studentfirstname, P1.lastname AS studentlastname, P1.id AS studentid, hold, degreename, P2.firstname AS advisorfirstname, P2.lastname AS advisorlastname, P2.id AS advisorid FROM personalinfo AS P1, personalinfo AS P2, roles AS R1, roles AS R2,  advises WHERE R1.role='STUDENT' AND R1.id = P1.id AND advises.studentid = P1.id AND P2.id = advises.facultyid AND R2.role = 'ADVISOR' AND R2.id = advises.facultyid;";

$result = mysqli_query($connection, $query);



echo "<table>
<tr>
<th>Name</th>
<th>Student ID</th>
<th>Hold</th>
<th>Degree Name</th>
<th>Faculty Advisor</th>
<th>Faculty ID</th>
<th>&nbsp;&nbsp;&nbsp;Assign</th>
</tr>";

while($row = mysqli_fetch_assoc($result)){
	
	echo "<tr>
	<td>".$row['studentfirstname']." ".$row['studentlastname']."</td>
	<td>".$row['studentid']."</td>
	<td>".$row['hold']."</td>
	<td>".$row['degreename']."</td>
	<td>".$row['advisorfirstname']." ".$row['advisorlastname']."</td>
	<td>".$row['advisorid']."</td>";

	$facultyquery = "SELECT firstname AS facultyfirstname,lastname AS facultylastname,personalinfo.id AS facultyid FROM personalinfo,roles WHERE personalinfo.id = roles.id AND roles.role='ADVISOR';";
	$facultyresult = mysqli_query($connection,$facultyquery);
	echo "<td><form method = 'post'><select name ='facultyidother'>";
	while($row1 = mysqli_fetch_assoc($facultyresult)){
		echo "<option value ='".$row1['facultyid']."' name='facultyidother'>".$row1['facultyfirstname']." ".$row1['facultylastname']."</option>
		</select>
		<input type='hidden' name='studentidother' value ='".$row['studentid']."'>
		<input type='submit' value='Assign' name='assign1'>";
	}
	echo "</form></td>";
	echo "</tr>";
}
echo "</table>";

echo "<h1>Assign New Faculty Advisor</h1>";






$current_students = "SELECT firstname,lastname,personalinfo.id,degreename,hold FROM personalinfo, advises, roles WHERE personalinfo.id = roles.id AND roles.role = 'STUDENT' AND advises.studentid = roles.id AND advises.facultyid IS NULL;";
$current_students_result = mysqli_query($connection, $current_students);

$facultyquery = "SELECT firstname AS facultyfirstname,lastname AS facultylastname,personalinfo.id AS facultyid FROM personalinfo,roles WHERE personalinfo.id = roles.id AND roles.role='ADVISOR';";
$facultyresult = mysqli_query($connection,$facultyquery);


echo "<table>
<tr>
<th>Name</th>
<th>Student ID</th>
<th>Hold</th>
<th>Degree Name</th>
<th>&nbsp;&nbsp;&nbsp;Assign</th>
</tr>";

while($row = mysqli_fetch_assoc($current_students_result)){
	$studentidnew = $row['id'];
	echo "<tr>
	<td>".$row['firstname']." ".$row['lastname']."</td>
	<td>".$row['id']."</td>
	<td>".$row['hold']."</td>
	<td>".$row['degreename']."</td>
	<td><form method='post'><select name ='facultyid'>";
	while($row1 = mysqli_fetch_assoc($facultyresult)){
	echo "<option value ='".$row1['facultyid']."' name='facultyidnew'>".$row1['facultyfirstname']." ".$row1['facultylastname']."</option>
		<input type='hidden' name='studentidnew' value ='$studentidnew'>
		<input type='hidden' name='facultyidnew' value ='".$row1['facultyid']."'>
		</select>
		<input type='submit' value='Assign' name='assign2'>
		</form></td>";
	}
	echo "</tr>";
}
echo "</table>";

?>



</body>
</html>