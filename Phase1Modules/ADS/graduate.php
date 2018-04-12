<?php
	// Removes all information about a student and inserts it into corresponding alumni tables

	// login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role = 'GRAD_SECRETARY' OR role='SYSTEM_ADMIN'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];

	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	} else if (isset($_POST['gwid']) && strcmp($_POST['action'], 'accept') == 0){
		// IF logged in and the form was accepted
		$gwid = $_POST['gwid'];
		// get student information
		$student_query = "SELECT firstname, lastname, gwid, major, address, email, username FROM students WHERE gwid='$gwid';";
		$result = mysqli_query($conn, $student_query);
		$row = mysqli_fetch_assoc($result);

		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$gwid = $row['gwid'];
		$major = $row['major'];
		$address = $row['address'];
		$email = $row['email'];
		$username = $row['username'];

		//copy to alumni
		$alumni_insert_query = "INSERT INTO alumni (firstname, lastname, gwid, degree_name, year, address, email, username) VALUES
		('$firstname', '$lastname', '$gwid', 'ms_cs', '2018', 
			'$address', '$email', '$username'); 
		";
		$result = mysqli_query($conn, $alumni_insert_query);

		// copy courses
		$courses_query = "SELECT * FROM course_status
		 WHERE gwid = '$gwid';";
		$result = mysqli_query($conn, $courses_query);

		while ($row = mysqli_fetch_assoc($result)) {
			$coursenum = $row['coursenum'];
			$c_gwid = $row['gwid'];
			$grade = $row['grade'];
			$insert_course_query = "INSERT INTO alumni_course_status (coursenum, gwid, grade) VALUES 
			('$coursenum', '$c_gwid', '$grade');
			";
			$course_result = mysqli_query($conn, $insert_course_query);
		}

		// update permissions
		$update_role_query = "UPDATE login SET role='ALUMNI' 
		WHERE username='$username';";
		$result = mysqli_query($conn, $update_role_query);

		// delete courses
		$courses_delete_query = "DELETE FROM course_status 
		WHERE gwid='$gwid';";
		$result = mysqli_query($conn, $courses_delete_query);

		// delete application;
		$application_delete_query = "DELETE FROM applications
		WHERE gwid='$gwid';";
		$result = mysqli_query($conn, $application_delete_query);

		// delete advises
		$advises_delete = "DELETE FROM advises WHERE gwid='$gwid';";
		$result = mysqli_query($conn, $advises_delete);

		//delete student
		$student_delete_query = "DELETE FROM students
		WHERE gwid='$gwid'";
		$result = mysqli_query($conn, $student_delete_query);
		echo "<p>Successful<p>";
	} else if (isset($_POST['gwid']) && strcmp($_POST['action'], 'deny') == 0){
		// If logged in and form was denied
		$gwid = $_POST['gwid'];
		$delete_query = "DELETE FROM applications WHERE gwid='$gwid';";
		$result = mysqli_query($conn, $delete_query);
		if ($result) {
			echo "Successful";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Graduation</title>
	<link rel ="stylesheet" type="text/css" href="style1.css"/>
</head>
<body>
	<a href="gs.php">Back</a>
	<br>
	<b><a href="logout.php">Log Out</a></b>
</body>
</html>