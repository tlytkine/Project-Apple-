<?php include 'header.php';?>
<?php include 'db-connect.php';?>

<html>
<head><title>View / Edit Faculty Advisor</title></head>
<body>
<h1>View / Edit Faculty Advisor</h1>
<?php 
$id = $_SESSON["id"];
// Faculty advisor query 
// Faculty advisor result 
// Put Faculty advisors into drop down menu with fid as hidden value 

$gwid = $_POST['gwid'];

// Gets information about a student
$student_query = "SELECT firstname, lastname, gwid 
				  FROM students 
				  WHERE gwid = '$gwid';";
$student_result = mysqli_query($conn, $student_query);

$student = mysqli_fetch_assoc($student_result);

// Gets information about all the faculty advisors
$faculty_query = "SELECT firstname, lastname, fid
				  FROM faculty;";
$fq_result = mysqli_query($conn,$faculty_query);


// Gets current faculty advisors
$current_advisor = "SELECT DISTINCT faculty.firstname, faculty.lastname, faculty.fid
					FROM faculty, advises 
					WHERE advises.gwid = '$gwid'
					AND advises.fid = faculty.fid;";
					
$ca_result = mysqli_query($conn,$current_advisor);
$row1 = mysqli_fetch_assoc($ca_result);

$fac_num = mysqli_num_rows($f_row);

//Display information
echo "<table>
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>GWID</th>
<th>Faculty Advisor</th>
<th>&nbsp;&nbsp;&nbsp;Assign</th>
</tr>
<tr>
<td>".$student['firstname']."</td>
<td>".$student['lastname']."</td>
<td>".$student['gwid']."</td>
<td>".$row1['firstname']." ".$row1['lastname']."</td>
<td><form method='post' action = 'fa_assign_submit.php'>
<select name ='fid'>";

	// Different options to select which faculty advisor should be assigned
	while($row = mysqli_fetch_assoc($fq_result)){
		echo "<option value ='".$row['fid']."'>".$row['firstname']." ".$row['lastname']."</option>";
	}
	// submit gets handled in fa_assign_submit.php
 echo "
</select>
<input type='submit' value='Assign'>
<input type='hidden' name='gwid' value ='".$student['gwid']."'>
</form></td>
</tr>
</table>";


?>



<b><a href="logout.php">Log Out</a></b>


</body>
</html>