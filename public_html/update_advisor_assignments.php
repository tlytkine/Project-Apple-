<?php include 'header.php';?>
<?php include 'db-connect.php';?>

<html>
<head><title>View / Edit Faculty Advisor</title></head>
<link rel="stylesheet" href="style.css">
<body>
<h1>View / Edit Faculty Advisor</h1>
<?php 
$id = $_SESSION["id"];
$studentid = $_POST["studentid"];




// Gets information about a student
$student_query = "SELECT personalinfo.id,firstname,lastname
				  FROM personalinfo, roles 
				  WHERE roles.role = 'STUDENT';";
$student_result = mysqli_query($connection, $student_query);



// Gets information about all the faculty advisors
$faculty_query = "SELECT firstname, lastname, personalinfo.id
				  FROM personalinfo, roles
				  WHERE roles.role='ADVISOR';";
$faculty_result = mysqli_query($connection,$faculty_query);



// Gets current faculty advisors
$current_advisor = "SELECT DISTINCT personalinfo.firstname, personalinfo.lastname, personalinfo.id, advises.hold, advises.degreename 
					FROM personalinfo, roles, advises
					WHERE advises.studentid = '$studentid'
					AND advises.facultyid = personalinfo.id
					AND roles.role='ADVISOR'
					AND roles.id = personalinfo.id';";
					
$ca_result = mysqli_query($connection,$current_advisor);


$students = mysqli_fetch_assoc($student_result);
$alladvisors = mysqli_fetch_assoc($faculty_result);
$currentadvisor = mysqli_fetch_assoc($ca_result);







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
	<td>".$students['firstname']." ".$students['lastname']."</td>
	<td>".$students['id']."</td>
	<td>".$currentadvisor['hold']."</td>
	<td>".$currentadvisor['degreename']."</td>
	<td>".$currentadvisor['firstname']." ".$currentadvisor['lastname']."</td>
	<td>".$currentadvisor['id']."</td>
	<td><form method='post' action='advisor_assign_submit.php'>
	<select name ='facultyid'>";
	while($row1 = mysqli_fetch_assoc($faculty_result)){
		echo "<option value ='".$alladvisors['id']."'>".$row1['firstname']." ".$row1['lastname']."</option>";
	}
	 echo "</select>
	<input type='submit' value='Assign'>
	<input type='hidden' name='studentid' value ='".$row['studentid']."'>
	</form></td>
	</tr>
	</table>";
 
}

?>



</body>
</html>