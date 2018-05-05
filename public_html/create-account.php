<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Create Account</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<h1>Create Account</h1>
<form method="POST" id="newaccount">
	<input type="email" name="email" placeholder="Email" maxlength="254" required>
	<input type="password" name="password" placeholder="Password" maxlength="255" required>
	<button type="submit">Create Account</button>
</form>

<?php
require 'password.php'; // Allows use of password_hash with PHP 5.4

session_start();
if (isset($_POST['email']) && isset($_POST['password'])) {
	include 'db-connect.php';

	// Prepare email and password:
	$email = mysqli_real_escape_string($connection, trim($_POST['email']));
	$password = mysqli_real_escape_string($connection, trim($_POST['password']));
	
	// Hash password:
	$hash = password_hash($password, PASSWORD_BCRYPT);
	
	// Look up account:
	$query = "SELECT email, password FROM users WHERE email='$email'";
	$result = mysqli_query($connection, $query);

	// Process result:
	$rows = mysqli_num_rows($result);
	if ($rows != 0) {
		echo "<script type='text/javascript'>alert('An account with that email address already exists');</script>";
	} else {
		// Insert into users table:
		$query = "INSERT INTO users(email, password) VALUE ('$email', '$hash')";
		$result = mysqli_query($connection, $query);
		if (!$result) {
			echo "<script type='text/javascript'>alert('Account could not be created');</script>";
			exit();
		}
		
		// Get id:
		$query = "SELECT id FROM users WHERE email='$email'";
		$result = mysqli_query($connection, $query);
		if (!$result) {
			echo "<script type='text/javascript'>alert('Account could not be created');</script>";
			exit();
		}
		$id = mysqli_fetch_array($result)["id"];
		
		$roles = array("APPLICANT", "USER");
		
		// Insert roles:
		foreach ($roles as $role) {
			$query = "INSERT INTO roles(id, role) VALUES ($id, '$role')";
			$result = mysqli_query($connection, $query);
			if (!$result) {
				echo "<script type='text/javascript'>alert('Account could not be created');</script>";
				exit();
			}
			if ($role == "APPLICANT") {
				include 'initialize-applicant.php';
			}
		}
		
		// Insert blank personalinfo row:
		$query = "INSERT INTO applicantpersonalinfo (id) VALUE ($id)";
		$result = mysqli_query($connection, $query);
		if (!$result) {
			echo "<script type='text/javascript'>alert('Account could not be created');</script>";
			exit();
		}
		
		echo "<script type='text/javascript'>alert('Account created');</script>";
		header("refresh:0 url=login.php");
	}
}

?>

</body>
</html>
