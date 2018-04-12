<?php
	// Grad secretary home page
	//Can assign students faculty advisors and accept/deny applications
	
	//login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role = 'GRAD_SECRETARY'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	}





?>
<html>
<head><title>Grad Secretary</title></head>
<link rel ="stylesheet" type="text/css" href="style1.css"/>
<body>
<h1>Grad Secretary Page</h1>
<?php
echo "<h2>Welcome ".$user_check."</h2><br>";

// query form pending applications
$applications_query = "SELECT DISTINCT firstname, lastname, gwid
	FROM applications;";
$applications_result = mysqli_query($conn,$applications_query);

/*Grad Secretary (GS)
Has complete access to applicant’s data and to current student’s data. They are responsible for (1) updating status of applicant, (2) matriculating a student (i.e., changing an admitted applicant to a current student once the student enrolls at GW), and (3) clearing a student for graduation. Note that they cannot create new users.
*/
echo "<h3>Applications</h3>";
if (mysqli_num_rows($applications_result) > 0) {
echo "<table>
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>GWID</th>
<th>Transcript</th>
<th>Holds</th>
<th>Clear for graduation</th></tr>";

// Display each application and various actions that can be performed
while($row = mysqli_fetch_assoc($applications_result)){
	$gwid = $row['gwid'];
	echo "<tr>
	<td>".$row['firstname']."</td>"."
	<td>".$row['lastname']."</td>"."
	<td>".$gwid."</td>";
	// View transcript
	echo "<td><form method ='post' action='transcript.php'>
	<input type='hidden' name='gwid' value ='".$gwid."'>
	<input type='submit' value='View Transcript'>
	</form></td>";

	$query = "SELECT hold FROM advises WHERE gwid = '$gwid';";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);

	// dispaly holds
	$display_accept;
	echo"<td>";
	if (strcmp($row['hold'], "NULL") != 0) {
		echo $row['hold'];
		$display_accept = false;
	}
	else {
		echo "No holds";
		$display_accept = true;
	}
	echo "</td>";

	if ($display_accept) {
		// Accepts the form to graduate
		echo "<td><form method ='post' action='graduate.php'>
		<input type='hidden' name='gwid' value ='".$gwid."'>
		<input type='hidden' name='action' value='accept'>
		<input type='submit' value='Approve'>
		</form></td>";
	}
	// Denies the form to graduate
	echo "<td><form method='post' action='graduate.php'>
	<input type='hidden' name='gwid' value ='".$gwid."'>
	<input type='hidden' name='action' value='deny'>
	<input type='submit' value='Deny'>
	</tr>";
}
echo "</table><br>";
}
else {
	echo "Currently no applications submitted.<br><br>";
}

// get information about all current students
$students_query = "SELECT firstname, lastname, gwid
	FROM students;";
$students_result = mysqli_query($conn,$students_query);



echo "<h3>Current Students</h3><br>";
echo "<table>
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>GWID</th>
<th>Transcript</th>
<th>Personal Information</th>
<th>Faculty Advisor</th>
</tr>";

// display all current students
while($row = mysqli_fetch_assoc($students_result)){
	echo "<tr>
	<td>".$row['firstname']."</td>"."
	<td>".$row['lastname']."</td>"."
	<td>".$row['gwid']."</td>";
	// View transcript
	echo "<td><form method ='post' action='transcript.php'>
	<input type='hidden' name='gwid' value ='".$row['gwid']."'>
	<input type='submit' value='View Transcript'>
	</form></td>";
	// View personal information
	echo "<td><form method ='post' action='personal_info_fa.php'>
	<input type='hidden' name='gwid' value ='".$row['gwid']."'>
	<input type='submit' value='View Personal Information'>
	</form></td>";
	// Assign a faculty advisor to the student
	echo "<td><form method ='post' action='fa_assign.php'>
	<input type='hidden' name='gwid' value ='".$row['gwid']."'>
	<input type='submit' value='View/Edit'>
	</form></td>
	</tr>";
}
echo "</table><br>";




?>

<b><a href="logout.php">Log Out</a></b>


</body>
</html>