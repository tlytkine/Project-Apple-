<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSON["id"];
	//Displays personal information AND allows user to update

	// Login script
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
		// If logged in fetch some information about the user
		$user_name = $_SESSION['login_user'];
		$query = "SELECT gwid 
			FROM students WHERE 
			username='$user_name';";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);
		$gwid = $row['gwid'];
		echo "<h2>View/Edit Personal Information</h2>";

		// If the form was previously submitted, update fields to new values
		// email
		$updated = false;
		if (strcmp($_POST['update'], "true") == 0 && !empty($_POST['email'])) {
			$query = "SELECT email FROM students WHERE username='$user_name';";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_assoc($result);

			if (strcmp($row['email'], $_POST['email']) != 0 ){
				if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
					$query = "UPDATE students
						SET students.email = '" . $_POST['email'] . "'
						WHERE students.gwid = '$gwid';";
					$result_cleared_query = mysqli_query($conn, $query);
					echo "Updated email <br>";
					$updated = true;
				} else {
					echo "<b>Email is in incorrect format.</b><br>";
				}
			}
		}
		//address
		if (strcmp($_POST['update'], "true") == 0 && !empty($_POST['address'])) {
			$query = "SELECT address FROM students WHERE username='$user_name';";
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_assoc($result);

			if (strcmp($row['address'], $_POST['address']) != 0) {
				$query = "UPDATE students
					SET students.address = '" . $_POST['address'] . "'
					WHERE students.gwid = '$gwid';";
				$result_cleared_query = mysqli_query($conn, $query);
				echo "Updated address <br>";
				$updated = true;
			}
		}

		if (strcmp($_POST['update'], "true") == 0 && !$updated) {
			echo "<b>No changes made.</b></br>";
		}

		// Query for updated/old information about the user and
		// store them
		$query = "SELECT email, address 
			FROM students WHERE 
			username='$user_name';";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);
		$email = $row['email'];
		$address = $row['address'];
	}


?>
<!DOCTYPE html>
<html>
<head>
	<title>View/Edit Personal Information</title>
	<link rel ="stylesheet" type="text/css" href="style1.css"/>
</head>
<body>
	<!-- Form to update information-->
	<form method="post" action="personal_info.php">
		<!-- Displays the email -->
		<p>Email: </p>
		<input type="text" name="email" value= <?php echo '"' . $email . '"'; ?>>
		<!-- Address -->
		<p>Address: </p>
		<input type="text" name="address" value= <?php echo '"' . $address .'"'; ?>>
		<!-- Redirects to same file, and uses the update queries at the top-->
		<input type="hidden" name="update" value="true">
		<input type="submit" name="submit" value="Update Personal Information">
	</form>
	<form method="post" action="form1.php">
		<input type="submit" name="submit" value="Back">
	</form>
</body>
</html>