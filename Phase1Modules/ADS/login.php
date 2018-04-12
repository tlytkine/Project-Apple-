<?php
	// Home page of the system
	// Logs a user in and redirects them to the appropriate view

	// create a session
	session_start();
	$error='';
	//if the login form was submitted
	if (isset($_POST['submit'])) {
		// check if input is valid
		if (empty($_POST['username']) || empty($_POST['password'])) {
			$error = "Username or Password is invalid";
		}
		else {
			$username=$_POST['username'];
			$password=$_POST['password'];
			// else connect to database
			$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");
			if (!$conn)	{	
				die("Connection failed: " . mysqli_connect_error());	
			}	
			// sanatize input
			$username = stripslashes($username);
			$password = stripslashes($password);

			// check if login is real
			$query = "select * from login where password ='$password' AND username='$username'";
			$result = mysqli_query($conn, $query);
			//if a username/password pair exists
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);
				$_SESSION['login_user'] = $username;
				$_SESSION['role'] = $row['role'];
				// redirect to appropriate page
				if (strcmp($row['role'], 'SYSTEM_ADMIN') == 0) {
					header("location: admin.php");
					exit;
				}if (strcmp($row['role'], 'GRAD_SECRETARY') == 0) {
					header("location: gs.php");
					exit;
				}if (strcmp($row['role'], 'FACULTY_ADVISOR') == 0) {
					header("location: fa.php");
					exit;
				}if (strcmp($row['role'], 'STUDENT') == 0) {
					header("location: form1.php");
					exit;
				}if (strcmp($row['role'], 'ALUMNI') == 0) {
					header("location: alumni.php");
					exit;
				}
			} else {
				$error = "Username or Password is invalid";
			}
			mysqli_close($conn);
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>ADS Login</title>
	<link rel ="stylesheet" type="text/css" href="style1.css"/>
</head>
<body>
	<h1>ADS Login</h1>
	<h2>Login Form </h2>
	<!--Login Form-->
	<form action="" method="post">
		<!-- UserName -->
		<label>User Name: </label>
		<input name="username" placeholder="username" type="text">
		&nbsp;&nbsp;
		<!-- Pasword -->
		<label>Password: </label>
		<input name="password" placeholder="********" type="password">
		<input type="submit" name="submit" value="Login">
		<span><?php echo $error; ?></span>
	</form>

<br>
<!-- Reset button, resets the database to an initial state -->
<form action="reset.php" method="post">
<input type="submit" name="reset" value="Reset database">
</form>

</body>
</html>