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
<h1>Manage Users</h1>
<br/>
New User:
<form method="POST" id="newuser">
	<input type="email" name="email" placeholder="Email" maxlength="254" required>
	<input type="password" name="password" placeholder="Password" maxlength="255" required>
	<select name="type" required>
		<option value="" selected disabled>User Type</option>
		<option value="System Administrator">System Administrator</option>
		<option value="Grad Secretary">Grad Secretary</option>
		<option value="CAC">Chair of Admissions Committee</option>
		<option value="Faculty Reviewer">Faculty Reviewer</option>
		<option value="Applicant">Applicant</option>
	</select>
	<button type="submit">Create User</button>
</form>

<?php
require 'password.php'; // Allows use of password_hash with PHP 5.4

if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['type'])) {
	include 'db-connect.php';

	// Prepare email, password, and type:
	$email = mysqli_real_escape_string($connection, trim($_POST['email']));
	$password = mysqli_real_escape_string($connection, trim($_POST['password']));
	$type = mysqli_real_escape_string($connection, trim($_POST['type']));
	
	// Hash password:
	$hash = password_hash($password, PASSWORD_BCRYPT);
	
	// Look up account:
	$query = "SELECT email, password, type FROM users WHERE email='$email'";
	$result = mysqli_query($connection, $query);
	
	// Process result:
	$rows = mysqli_num_rows($result);
	if ($rows != 0) {
		echo "<script type='text/javascript'>alert('An account with that email address already exists');</script>";
	} else {
		$query = "INSERT INTO users(email, password, type) VALUE('$email', '$hash', '$type')";
		$result = mysqli_query($connection, $query);
		if ($result) {
			echo "<script type='text/javascript'>alert('Account created');</script>";
		} else {
			echo "<script type='text/javascript'>alert('Account could not be created');</script>";
		}
	}
}

?>

</body>
</html>
