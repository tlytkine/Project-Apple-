<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Manage Users </title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("System Administrator");
include 'header.php';
?>
<h1>Add Users</h1>
<br/>
<form method="POST" id="newuser">
	<input type="email" name="email" placeholder="Email" maxlength="254" required>
	<input type="password" name="password" placeholder="Password" maxlength="255" required>
	<br /><br />
	<select multiple name="roles[]" required>
		<option value="" selected disabled>Roles</option>
		<option value="ADMIN">System Administrator</option>
		<option value="GS">Grad Secretary</option>
		<option value="INSTRUCTOR">Instructor</option>
		<option value="ADVISOR">Advisor</option>
		<option value="REVIEWER">Faculty Reviewer</option>
		<option value="CAC">Chair of Admissions Committee</option>
		<option value="APPLICANT">Applicant</option>
		<option value="STUDENT">Student</option>
		<option value="ALUMNI">Alumni</option>
	</select>
	<br /><br />
	<button type="submit">Add User</button>
</form>

<?php
require 'password.php'; // Allows use of password_hash with PHP 5.4

if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['roles'])) {
	include 'db-connect.php';

	// Prepare email and password:
	$email = mysqli_real_escape_string($connection, trim($_POST['email']));
	$password = mysqli_real_escape_string($connection, trim($_POST['password']));
	
	// Prepare roles:
	$roles = array();
	foreach ($_POST['roles'] as $role) {
		$roles[] = mysqli_real_escape_string($connection, trim($role));
	}
	$roles[] = "USER"; // Add USER role by default
	
	// Hash password:
	$hash = password_hash($password, PASSWORD_BCRYPT);
	
	// Look up account:
	$query = "SELECT email FROM users WHERE email='$email'";
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
		
		// Insert roles:
		foreach ($roles as $role) {
			$query = "INSERT INTO roles(id, role) VALUES ($id, '$role')";
			$result = mysqli_query($connection, $query);
			if (!$result) {
				echo "<script type='text/javascript'>alert('Account could not be created');</script>";
				exit();
			}
		}
		echo "<script type='text/javascript'>alert('Account created');</script>";
	}
}

?>

</body>
</html>
