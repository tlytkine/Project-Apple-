<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];

?>


<html>
<head><title>Approve/Deny Graduation Applications</title></head>
<link rel="stylesheet" href="style.css">
<body>
<h1>Graduation Applications</h1><br> 
<?php


$approve = $_POST["approve"];
$sid = $_POST["studentid"];

if($approve){
	$studentinfoquery = "SELECT * FROM personalinfo WHERE id = $sid;";
	$studentinforesult = mysqli_query($connection, $studentinfoquery);

	$studentinfo = mysqli_fetch_assoc($studentinforesult);

	$firstname = $studentinfo['firstname'];
	$lastname = $studentinfo['lastname'];
	$dob = $studentinfo['dob'];
	$address = $studentinfo['address'];
	$ssn = $studentinfo['ssn'];


	$currentyearquery = "SELECT year FROM courses LIMIT 1;";
	$currentyearresult = mysqli_query($connection, $currentyearquery);

	$currentyear = mysqli_fetch_assoc($currentyearresult);

	$year = $currentyear['year'];


	$degreenamequery = "SELECT degreename FROM advises WHERE studentid=$sid;";
	$degreenameresult = mysqli_query($connection, $degreenamequery);
	$degreenamefetch = mysqli_fetch_assoc($degreenameresult);

	$degreename = $degreenamefetch['degreename'];

	$insertalumniquery = "INSERT INTO alumnipersonalinfo(id,firstname,lastname,dob,address,graduationyear,degreename,ssn) VALUES($sid,'$firstname','$lastname','$dob','$address',$year,'$degreename','$ssn');";
	$insertalumniresult = mysqli_query($connection, $insertalumniquery);




	$deletestudentquery = "DELETE FROM personalinfo WHERE id = '$sid';";
	$deletestudentresult = mysqli_query($connection, $deletestudentquery);

	$updaterolequery = "UPDATE roles SET role='ALUMNI' WHERE id='$sid' AND ROLE='STUDENT';";
	$updateroleresult = mysqli_query($connection, $updaterolequery);

	echo "Student is now an alumni!";

}

if($deny){
	$denyquery = "DELETE FROM graduationapplication WHERE studentid='$sid';";
	$denyresult = mysqli_query($connection, $denyquery);
	echo "Students graduation application deleted from system!";
}



$applications_query = "SELECT DISTINCT graduationapplication.studentid,personalinfo.firstname,personalinfo.lastname, graduationapplication.year,graduationapplication.cleared, advises.degreename FROM graduationapplication,personalinfo,advises
	WHERE graduationapplication.studentid = personalinfo.id AND advises.studentid = graduationapplication.studentid;";
$applications_result = mysqli_query($connection, $applications_query);



echo "<table>
<tr>
<th>Student ID</th>
<th>Name</th>
<th>Year</th>
<th>Degree Name</th>
<th>Application Status</th>
<th>Approve</th>
<th>Deny</th>
</tr>";

while($row=mysqli_fetch_assoc($applications_result)){
	echo "<tr>
	<td>".$row['studentid']."</td>
	<td>".$row['firstname']." ".$row['lastname']."</td>
	<td>".$row['year']."</td> 
	<td>".$row['degreename']."</td>
	<td>";
	if($row['cleared'] == 1){
		echo "Cleared.";
	}
	else {
		echo "Not cleared.</td>";
	}
	echo "<td>";
	echo "<form method='post' action='approve_deny_graduation_applications.php'>
	<input type='submit' value='Approve' name='approve'>
	<input type='hidden' value='$studentid' name='studentid'>
	</form>";
	echo "</td>";
	echo"<td>"; 
	echo "<form method='post' action='approve_deny_graduation_applications.php'>
	<input type='submit' value='Deny' name='deny'>
	<input type='hidden' value='$studentid' name='studentid'>
	</form>";
	echo"</td>";
}
echo "</table>";


$deny = $_POST["deny"];
$approve = $_POST["approve"];

if($approve){
	$studentid = $_POST["studentid"];
	$approve_query = "DELETE FROM graduationapplication WHERE studentid='$studentid';";
	$approve_result = mysqli_query($connection,$approve_query);

	$change_to_alumni_query = "UPDATE roles SET role ='ALUMNI' WHERE id='$studentid';";
	$change_to_alumni_result = mysqli_query($connection,$change_to_alumni_query);
}
else if($deny){
	$studentid = $_POST["studentid"];
	$approve_query = "DELETE FROM graduationapplication WHERE studentid='$studentid';";
	$approve_result = mysqli_query($connection,$approve_query);

}







?>