<?php
	// Only allows for viewing personal information

	// login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role='FACULTY_ADVISOR' OR role='SYSTEM_ADMIN';");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	} else {
		// if logged in, fetch information about passed in student
		$gwid = $_POST['gwid'];

		echo "<h2>View Personal Information</h2>";


		// query for information and store
		$query = "SELECT firstname, lastname, SSN, email, address 
			FROM students WHERE 
			gwid = '$gwid'";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);
		$first_name = $row['firstname'];
		$last_name = $row['lastname'];
		$SSN = $row['SSN'];
		$email = $row['email'];
		$address = $row['address'];
	}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Personal Information</title>
	<link rel ="stylesheet" type="text/css" href="style1.css"/>
</head>
<body>
<h1>Personal Information: <br></h1>
<?php
// display personal information, cannot edit
echo "First Name: ".$first_name."<br>";
echo "Last Name: ".$last_name."<br>";
echo "GWID: ".$gwid."<br>";
echo "SSN: ".$SSN."<br>";
echo "Email: ".$email."<br>";
echo "Address: ".$address."<br>";
?>

</form>
<a href='logout.php'>Logout</a>
<button onclick="history.go(-1);">Back </button>
</body>
</html>
