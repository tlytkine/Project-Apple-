<?php
	// Faculty advisor home page
	// Can set holds, view transcripts

	// Login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role = 'FACULTY_ADVISOR'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	}
?>
<html>
<head><title>Faculty Advisor</title></head>
<link rel ="stylesheet" type="text/css" href="style1.css"/>
<body>

<?php
// Selects information about the user
$name_query = "SELECT firstname, lastname, fid 
			   FROM faculty 
			   WHERE username = '$user_check';";
$name_result = mysqli_query($conn, $name_query);

$row = mysqli_fetch_assoc($name_result);

echo "<h1> Welcome ".$row['firstname']." ".$row['lastname']."!</h1><br>";
echo "<p>Faculty ID: ".$row['fid']."</p><br>";

$facultyid = $row['fid'];

// Gets all students that the faculty advisor is responsible for
$advisee_query = "SELECT students.firstname, students.lastname, students.gwid, students.cleared, advises.hold 
FROM students, advises
WHERE advises.fid = '$facultyid' AND 
advises.gwid = students.gwid;";
$advisee_result = mysqli_query($conn, $advisee_query);




echo "<table>
<tr><th>Advisee</th>
<th>GWID</th>
<th>Transcript</th>
<th>Information</th>
<th>Application Status</th>
<th>View Holds</th>
<th>Lift Holds</th>
<th>Place Holds</th>
</tr>";

// Displays a table of all students along with actions that can be taken
while ($row = mysqli_fetch_assoc($advisee_result)){
echo "<tr><td>".$row['firstname']." ".$row['lastname']."</td>
<td>".$row['gwid']."</td>";
// Display transcript
echo "<td><form method ='post' action='transcript.php'>
<input type='hidden' name='gwid' value ='".$row['gwid']."'>
<input type='submit' value='View Transcript'>
</form></td>";
// Display personal information
echo "<td><form method ='post' action='personal_info_fa.php'>
<input type='hidden' name='gwid' value ='".$row['gwid']."'>
<input type='submit' value='View Personal Information'>
</form></td>";
// Cleared for graduation
if($row['cleared']==1){
	echo "<td>Cleared</td>";
}
else {
	echo "<td>Not cleared</td>";
}
// display any holds
echo"<td>";
if (strcmp($row['hold'], "NULL") != 0) {
	echo $row['hold'];
}
echo "</td>
<form method ='post' action='hold.php'>
<input type='hidden' name='gwid' value ='".$row['gwid']."'>
<input type='hidden' name='fid' value='$facultyid'>
<td>
<input type='submit' name='lift' value='Lift Hold'>
</form>
</td>
<form method='post' action='hold.php'>
<input type='hidden' name='gwid' value ='".$row['gwid']."'>
<input type='hidden' name='fid' value='$facultyid'>
<td>
<input type='text' name='holdtext'>
<input type='submit' name='place' value='Place Hold'>
</td>
</form>
</tr>";
}
echo "</table>";
?>


<b><a href="logout.php">Log Out</a></b>
</body>
</html>