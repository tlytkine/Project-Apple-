<?php 

$allowed_user_types = array(
        "ADMIN",
        "GS"
 );
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];

?>


<html>
<head><title>Approve/Deny Graduation Applications</title></head>
<link rel="stylesheet" href="style.css">
<body>
<h1>Graduation Applications</h1> 
<?php


	$approve = $_POST['approve'];
	$deny = $_POST['deny'];
	$sid = $_POST['studentid'];



	// check if there are applications present in the system 

	$check_applications_query = "SELECT * FROM graduationapplication;";
	$check_applications_result = mysqli_query($connection, $check_applications_query);
	$check = mysqli_fetch_assoc($check_applications_result);

	if(!(ISSET($check['studentid']))){
		echo "There are currently no graduation applications present in the system.";
	}
	else {
		$applications_query = "SELECT DISTINCT graduationapplication.studentid,personalinfo.firstname,personalinfo.lastname, graduationapplication.year,graduationapplication.cleared, advises.degreename FROM graduationapplication,personalinfo,advises
		WHERE graduationapplication.studentid = personalinfo.id AND advises.studentid = graduationapplication.studentid;";
		echo $applications_query;
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
			echo "<form method='post'>
			<input type='submit' value='Approve' name='approve'>
			<input type='hidden' value='".$row['studentid']."' name='studentid'>
			</form>";
			echo "</td>";
			echo"<td>"; 
			echo "<form method='post'>
			<input type='submit' value='Deny' name='deny'>
			<input type='hidden' value='".$row['studentid']."' name='studentid'>
			</form>";
			echo"</td>";
		}
		echo "</table>";

		$approve = $_POST['approve'];
		$deny = $_POST['deny'];
		$sid = $_POST['studentid'];

		if($approve){
			$sid = $_POST['studentid'];
			$studentinfoquery = "SELECT * FROM personalinfo WHERE id = $sid;";
			$studentinforesult = mysqli_query($connection, $studentinfoquery);

			$studentinfo = mysqli_fetch_assoc($studentinforesult);

			$firstname = $studentinfo['firstname'];
			$lastname = $studentinfo['lastname'];
			$dob = $studentinfo['dob'];
			$address = $studentinfo['address'];
			$ssn = $studentinfo['ssn'];


			$currentyearquery = "SELECT year,semester FROM courses LIMIT 1;";
			$currentyearresult = mysqli_query($connection, $currentyearquery);

			$currentyear = mysqli_fetch_assoc($currentyearresult);

			$year = $currentyear['year'];
			$semester = $currentyear['semester'];


			$degreenamequery = "SELECT degreename FROM advises WHERE studentid= $sid;";
			$degreenameresult = mysqli_query($connection, $degreenamequery);
			$degreenamefetch = mysqli_fetch_assoc($degreenameresult);

			$degreename = $degreenamefetch['degreename'];

			$insertalumniquery = "INSERT INTO alumnipersonalinfo(id,firstname,lastname,dob,address,graduationyear,graduationsemester,degreename,ssn) VALUES($sid,'$firstname','$lastname','$dob','$address','$year','$semester','$degreename','$ssn');";
			$insertalumniresult = mysqli_query($connection, $insertalumniquery);


			$deletestudentquery = "DELETE FROM personalinfo WHERE id = $sid;";
			$deletestudentresult = mysqli_query($connection, $deletestudentquery);

			$updaterolequery = "UPDATE roles SET role='ALUMNI' WHERE id= $sid AND role='STUDENT';";
			$updateroleresult = mysqli_query($connection, $updaterolequery);

			if($updateroleresult){
				echo "Student is now an alumni!";
			}
			else{
				echo "Role was not updated.";
			}
			$delete_student_query = "DELETE FROM graduationapplication WHERE studentid = $sid;";
			$delete_student_result = mysqli_query($connection, $delete_student_query);
		}
		if($deny){
			$sid = $_POST['studentid'];
			$denyquery = "DELETE FROM graduationapplication WHERE studentid = $sid;";
			$denyresult = mysqli_query($connection, $denyquery);
			if($denyresult){
				echo "Students graduation application deleted from system!";
			}
			else{
				echo "Error deleting student from system.";
			}
		}
	}


?>