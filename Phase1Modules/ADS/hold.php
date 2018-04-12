<?php
	// Used to life/place an advising hold

	//Login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role = 'FACULTY_ADVISOR' OR role='SYSTEM_ADMIN'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	}
?>
<html>
<head><title>View / Edit Holds </title></head>
<link rel ="stylesheet" type="text/css" href="style1.css"/>
<body>
<?php

$gwid = $_POST['gwid'];
$fid = $_POST['fid'];



# Lift Hold Button 
if (isset($_POST['lift'])) {
	echo "<h1>Lift Hold</h1><br>";
	$lift_query = "UPDATE advises
	SET hold='NULL'
	WHERE gwid = '$gwid' AND 
	fid = '$fid'";
	$lift_result = mysqli_query($conn, $lift_query);
	if(mysqli_affected_rows($conn)>0){
		echo "Hold was successfully lifted!<br>";
	}
	else {
		echo "There was not a hold in place to be lifted.<br>";
	}
}



# Place Hold Button 
if (isset($_POST['place'])) {
	echo "<h1>Place Hold</h1><br>";
	if(isset($_POST['holdtext'])){
		$holdtext = $_POST['holdtext'];
		if(strlen($holdtext)>0){
			$place_query = "UPDATE advises
			SET hold='$holdtext'
			WHERE gwid = '$gwid' AND 
			fid = '$fid'";

			$place_result = mysqli_query($conn, $place_query);
			if(mysqli_affected_rows($conn)>0){
				echo "Hold was successfully placed!<br>";
			}
			else {
				echo "Please try a different hold.<br>";
			}
		}
		else {
			echo "Hold was not placed. Please enter a hold in the text field.<br>";
		}
	}
}






?>
<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Back</a><br>
<b><a href="logout.php">Log Out</a></b>
</body>
</html>