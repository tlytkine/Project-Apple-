<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<h1>Login</h1>
<form method="POST" id="login">
	<input type="email" name="email" placeholder="Email" maxlength="254" required>
	<input type="password" name="password" placeholder="Password" maxlength="255" required>
	<button type="submit">Login</button>
</form>

<a href="create-account.php">Create Applicant Account</a><br/><br/>
<a href="reset.php">Reset Database</a>

<?php
require 'password.php'; // Allows use of password_verify with PHP 5.4

session_start();

if (isset($_POST['email']) && isset($_POST['password'])) {
	include 'db-connect.php';

	// Prepare email and password:
	$email = mysqli_real_escape_string($connection, trim($_POST['email']));
	$password = mysqli_real_escape_string($connection, trim($_POST['password']));
	
	// Look up account:
	$query = "SELECT email, password, id FROM users WHERE email='$email'";
	$result = mysqli_query($connection, $query);

	// Process result:
	$rows = mysqli_num_rows($result);
	if ($rows != 1) {
		echo "<script type='text/javascript'>alert('Invalid email or password');</script>";
	} else {
		$row = mysqli_fetch_array($result);
		if (password_verify($password, $row["password"])) {
			$_SESSION["email"] = $row["email"];
			$_SESSION["id"] = $row["id"];
			
			$query = "SELECT role FROM roles WHERE id={$_SESSION['id']}";
			$result = mysqli_query($connection, $query);
			
			$roles = array();
			while ($row = mysqli_fetch_array($result)) {
				 $roles[] = $row["role"];
			}
			
			$_SESSION["roles"] = $roles;

			header("location: menu.php");
		} else {
			echo "<script type='text/javascript'>alert('Invalid email or password');</script>";
		}
	}
}
?>

</body>
</html>
