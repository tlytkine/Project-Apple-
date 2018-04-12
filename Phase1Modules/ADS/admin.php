<?php
	//File displays all actions the system admin can perform

	// login script
	// connect to database
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	// get user from database
	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role = 'SYSTEM_ADMIN'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	// check the result of the query to determine if the user is logged
	// in and has the appropirate permissions
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	}
?>
<html>
<head><title>System Admin</title></head>
<link rel ="stylesheet" type="text/css" href="style1.css"/>
<body>
<b>Welcome to System Admin</b>
<br>
<b><a href="logout.php">Log Out</a></b>
<br>

<?php 
//Get list of students
$advisee_query = "SELECT firstname, lastname, gwid, SSN, cleared FROM students";
/*$advisee_query = "SELECT students.firstname, students.lastname, students.gwid, students.cleared, advises.fid, advises.hold
FROM students, advises
WHERE advises.gwid = students.gwid;";*/
$hold_query = "SELECT advises.fid, advises.hold FROM students, advises WHERE advises.gwid = students.gwid;";
$advisee_result = mysqli_query($conn, $advisee_query);
$hold_result = mysqli_query($conn, $hold_query);


echo "<h2>Students</h2>";
$num_rows = mysqli_num_rows($advisee_result);
echo "<b>Number of students enrolled: $num_rows</b></br>";
if ($num_rows > 0) {
echo "<table>
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>GWID</th>
<th>Transcript</th>
<th>Information</th>
<th>Application Status</th>
<th>View Holds</th>
<th>Lift Holds</th>
<th>Place Holds</th>
<th>Remove Student</th></tr>";

// Foreach student, display various fields and actions that can be taken
while ($row = mysqli_fetch_assoc($advisee_result)){
	// display identification info
	echo "<tr><td>".$row['firstname']."</td>
	<td>".$row['lastname']."</td>
	<td>".$row['gwid']."</td>";
	// transcript
	echo "<td><form method ='post' action='transcript.php'>
	<input type='hidden' name='gwid' value ='".$row['gwid']."'>
	<input type='submit' value='View Transcript'>
	</form></td>";
	// personal info
	echo "<td><form method ='post' action='personal_info_fa.php'>
	<input type='hidden' name='gwid' value ='".$row['gwid']."'>
	<input type='submit' value='View Personal Information'>
	</form></td>";
	// if the student is cleared for graduation
	if($row['cleared']==1){
		echo "<td>Cleared</td>";
	}
	else {
		echo "<td>Not cleared</td>";
	}
echo"<td>";

$gwid = $row['gwid'];
$row = mysqli_fetch_assoc($hold_result);
if (strcmp($row['hold'], "NULL") != 0) {
echo $row['hold'];
}
// any holds that might be on the account
echo "</td>
<form method ='post' action='hold.php'>
<input type='hidden' name='gwid' value ='$gwid'>
<input type='hidden' name='fid' value='".$row['fid']."'>
<td>
<input type='submit' name='lift' value='Lift Hold'>
</form>
</td>
<form method='post' action='hold.php'>
<input type='hidden' name='gwid' value ='$gwid'>
<input type='hidden' name='fid' value='".$row['fid']."'>
<td>
<input type='text' name='holdtext'>
<input type='submit' name='place' value='Place Hold'>
</td>
</form>";
	echo "<td><form method='post' action='user.php'>
		<input type='hidden' name='action' value='remove_student'>
		<input type='hidden' name='gwid' value = '$gwid'>
		<input type='submit' value='Remove'>
		</form></td></tr>";
}
echo "</table>";
} else {
	echo "Currently no students in the system.<br><br><br>";
}
// add a student 
echo "<form method='post' action='user.php'>
	<input type='hidden' name='action' value='input_student'>
	<input type='submit' value='Add Student'>
	</form>";


// Same but with current pending applications
$applications_query = "SELECT DISTINCT firstname, lastname, gwid
	FROM applications;";
$applications_result = mysqli_query($conn,$applications_query);

echo "<h2>Applications</h2>";
if (mysqli_num_rows($applications_result) > 0) {
echo "<table>
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>GWID</th>
<th>Transcript</th>
<th>Holds</th>
<th>Clear for graduation</th></tr>";

while($row = mysqli_fetch_assoc($applications_result)){
	$gwid = $row['gwid'];
	echo "<tr>
	<td>".$row['firstname']."</td>"."
	<td>".$row['lastname']."</td>"."
	<td>".$gwid."</td>";
	echo "<td><form method ='post' action='transcript.php'>
	<input type='hidden' name='gwid' value ='".$gwid."'>
	<input type='submit' value='View Transcript'>
	</form></td>";

	$query = "SELECT hold FROM advises WHERE gwid = '$gwid';";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);

	// display any holds
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
		echo "<td><form method ='post' action='graduate.php'>
		<input type='hidden' name='gwid' value ='".$gwid."'>
		<input type='hidden' name='action' value='accept'>
		<input type='submit' value='Approve'>
		</form></td>";
	}
	echo "<td><form method='post' action='graduate.php'>
	<input type='hidden' name='gwid' value ='".$gwid."'>
	<input type='hidden' name='action' value='deny'>
	<input type='submit' value='Deny'>
	</form></td></tr>";
}
echo "</table><br>";
}
else {
	echo "Currently no applications submitted.<br><br><br>";
}

// Displays current faculty
echo "<br><h2>Faculty</h2>";

$faculty_query = "SELECT firstname, lastname, fid, address, username
				 FROM faculty;";

$faculty_result =  mysqli_query($conn, $faculty_query);

echo "<table>
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>Faculty ID</th>
<th>Address</th>
<th>Username</th>
<th>Remove Faculty</th>
</tr>";

while($row = mysqli_fetch_assoc($faculty_result)){
	echo "<tr>
	<td>".$row['firstname']."</td>
	<td>&nbsp;&nbsp;".$row['lastname']."</td>
	<td>".$row['fid']."</td>
	<td>".$row['address']."</td>
	<td>".$row['username']."</td>"; 
	$fid = $row['fid'];
	echo "<td><form method='post' action='user.php'>
	<input type='hidden' name='action' value='remove_faculty'>
	<input type='hidden' name='fid' value='$fid'>
	<input type='submit' value='Remove'>
	</form></td>";"
	</tr>";
}
echo "</table>";

echo "<form method='post' action='user.php'>
	<input type='hidden' name='action' value='input_faculty'>
	<input type='submit' value='Add Faculty'>
	</form>";


// dispaly all degrees
echo "<br><h2>Degrees</h2>";
$degree_query = "SELECT degree_name, core1, core2, core3 
FROM degrees;";

$degree_result = mysqli_query($conn, $degree_query);

echo "<table>
<tr>
<th>Degree Name</th>
<th>Core Course 1</th>
<th>Core Course 2</th>
<th>Core Course 3</th>
</tr>";

while($row = mysqli_fetch_assoc($degree_result)){
	echo "<tr>
	<td>".$row['degree_name']."</td>
	<td>&nbsp;&nbsp;".$row['core1']."</td>
	<td>&nbsp;&nbsp;".$row['core2']."</td>
	<td>&nbsp;&nbsp;".$row['core3']."</td>
	</tr>";
}
echo "</table>";

echo "<table>
	<tr><td>";
echo "<form method='post' action='user.php'>
	<input type='hidden' name='action' value='input_degree'>
	<input type='submit' value='Add Degree'>
	</form>";
echo "<td><form method='post' action='user.php'>
	<input type='hidden' name='action' value='edit_degree_requirements'>
	<input type='submit' value='Edit Degree Requirements'>
	</form></td></tr></table>";

// Display all alumni
echo "<h2>Alumni</h2>";

$alumni_query = "SELECT firstname, lastname, gwid, degree_name, year,
				address, email, username
				FROM alumni;";
$alumni_result = mysqli_query($conn, $alumni_query);

echo "<table>
<tr>
<th>First Name</th>
<th>Last Name</th>
<th>GWID</th>
<th>Degree Name</th>
<th>Year</th>
<th>Address</th>
<th>Email</th>
<th>Username</th>
<th>Remove Alumni</th>
</tr>";

while($row = mysqli_fetch_assoc($alumni_result)){
	echo "<tr>
	<td>".$row['firstname']."</td>
	<td>".$row['lastname']."</td>
	<td>".$row['gwid']."</td>
	<td>".$row['degree_name']."</td>
	<td>".$row['year']."</td>
	<td>".$row['address']."</td>
	<td>".$row['email']."</td>
	<td>".$row['username']."</td>
	<td><form method='post' action='user.php'>
		<input type='hidden' name='action' value='remove_alumni'>
		<input type='hidden' name='gwid' value = '". $row['gwid'] ."'>
		<input type='submit' value='Remove'>
		</form></td>
		</tr>";
}
echo "</table>";

echo  "<form method='post' action='user.php'>
	<input type='hidden' name='action' value='input_alumni'>
	<input type='submit' value='Add Alumni'>
	</form>"
?>
</body>
</html>
