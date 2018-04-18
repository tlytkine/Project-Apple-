<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSON["id"];
	//Student home page
	//Can see information about the user as well as 
	//submit the graduation form

	//login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role = 'STUDENT'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	} else {
		// If logged in, query for information about the user
		// so part of the form is autofilled
		$user_name = $_SESSION['login_user'];
		$query = "SELECT firstname, lastname, gwid 
			FROM students WHERE 
			username='$user_name';";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$gwid = $row['gwid'];
		$ssn = $row['ssn'];


		$fid_query = "SELECT fid FROM advises WHERE gwid = '$gwid';";
		$fid_result = mysqli_query($conn, $fid_query);
		$row = mysqli_fetch_assoc($fid_result);
		$fid = $row['fid'];
		$fa_query = "SELECT firstname, lastname FROM faculty 
		WHERE fid = '$fid';";
		$fa = mysqli_query($conn,$fa_query);
		$row = mysqli_fetch_assoc($fa);
		$advisor_first = $row['firstname'];
		$advisor_last = $row['lastname'];

	}

?>

<html>
<head><title>Form 1</title></head>
<link rel ="stylesheet" type="text/css" href="style1.css"/>
<body>

<!-- Display some information that will get passed with the submitted form-->
<h1>Form 1</h1>
<form method="post" action="ads.php">
<p>First Name:</p>
<input type="text" name="first_name" value=
<?php echo '"' . $firstname . '"';	?> readonly>
<p>Last Name:</p>
<input type="text" name="last_name" value=
<?php echo '"' . $lastname . '"'; ?> readonly>
<p>Degree: </p>
<select name="degree">
<option value="ms_cs">MS CS</option>
</select>
<p>GW ID:</p>
<input type="text" name="gwid" value=
<?php echo '"' . $gwid . '"';	?> readonly>


<p>Faculty Advisor:</p>
<?php 
echo $advisor_first;
echo " ";
echo $advisor_last;
?> 



<h3>Courses Taken<br></h3>
<!-- Class Input -->
<p>Will not recognize any duplicate classes</p><br>
<?php 
	for ($i = 1; $i <= 12; $i++) {
		// Displays an drop down box with all available classes
		echo "<select name ='course$i'>";
		echo "<option value = '0'>-----</option>";
		$query = "SELECT coursenum, crn FROM courses;";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$coursenum = $row['coursenum'];
			$crn = $row['crn'];
			echo "<option value='$coursenum'>$crn</option>";
		}
		echo "</select>";
		echo "<br><br>";
	}

?>
<br>
<!-- Submits form 1-->
<input type="submit" value="Apply To Graduate">
&nbsp;&nbsp;
</form>
<br>
<!-- Displays transcript-->
<form method="post" action="transcript.php">
<input type='hidden' name='gwid' value=
<?php echo '"' . $gwid . '"';	?>>
<input type="submit" value="View Transcript">
</form>

<!-- Displays degree requirements-->
<form method="post" action="degree_requirements.php">
	<input type="submit" name="submit" value="View Degree Requirements">
</form>

<!-- Displays personal information-->
<form method="post" action="personal_info.php">
<input type="submit" value="View/Edit Personal Information">
</form>


<b><a href="logout.php">Log Out</a></b>


</body>
</html>
