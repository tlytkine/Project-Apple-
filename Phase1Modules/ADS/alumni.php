<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSON["id"];
?>

<html>
<head><title>Alumni</title></head>
<body>
<h1>Alumni</h1><br> 
<?php


	// Alumni home page
	// Can view and update personal information

	//login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role = 'ALUMNI'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	} else {
		// If logged in, fetch some information about the user
		$user_name = $_SESSION['login_user'];
		$query = "SELECT firstname, lastname, gwid, SSN, degree_name, year 
			FROM alumni WHERE 
			username='$user_name';";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);
		$gwid = $row['gwid'];
		$SSN = $row['SSN'];
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$degreename = $row['degree_name'];
		$year = $row['year'];

		echo "<h2>View/Edit Personal Information</h2>";

		// If a previous page submitted an update to the personal information, the update the database
		// Updates email
		if (strcmp($_POST['update'], "true") == 0 && !empty($_POST['email'])) {
			$query = "UPDATE alumni
				SET alumni.email = '" . $_POST['email'] . "'
				WHERE alumni.gwid = '$gwid';";
			$result_cleared_query = mysqli_query($conn, $query);
			echo "Updated email <br>";
		}
		// Updates address
		if (strcmp($_POST['update'], "true") == 0 && !empty($_POST['address'])) {
			$query = "UPDATE alumni
				SET alumni.address = '" . $_POST['address'] . "'
				WHERE alumni.gwid = '$gwid';";
			$result_cleared_query = mysqli_query($conn, $query);
			echo "Updated address <br>";
		}

		// Query for the current email and address
		$query = "SELECT email, address 
			FROM alumni WHERE 
			username='$user_name';";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);
		$email = $row['email'];
		$address = $row['address'];
	}
?><!DOCTYPE html>
<html>
<head>
	<title>View/Edit Personal Information</title>
	<i>You are only allowed to update your email and address.</i>
	<link rel ="stylesheet" type="text/css" href="style1.css"/>
</head>
<body>


	<!-- Display all personal information-->
	<form method="post" action="alumni.php">
		<p>First Name</p>
		<input type="text" name="firstname" value=
		<?php echo '"' . $firstname . '"';	?> readonly>
		<p>Last Name</p>
		<input type="text" name="lastname" value=
		<?php echo '"' . $lastname . '"';	?> readonly>
		<p>Degree Name</p>
		<input type="text" name="degreename" value=
		<?php echo '"' . $degreename . '"';	?> readonly>
		<p>Graduation Year</p>
		<input type="text" name="year" value=
		<?php echo '"' . $year . '"';	?> readonly>
		<p>GWID</p>
		<input type="text" name="gwid" value=
		<?php echo '"' . $gwid . '"';	?> readonly>
		<p>Email: </p>
		<input type="text" name="email" value= <?php echo '"' . $email . '"'; ?>>
		<p>Address: </p>
		<input type="text" name="address" value= <?php echo '"' . $address .'"'; ?>>
		<!--When submitted, loop back to this page with the update variable set-->
		<input type="hidden" name="update" value="true">
		<input type="submit" name="submit" value="Update Personal Information">
	</form>
<b><a href="logout.php">Log Out</a></b>
</body>
</html>