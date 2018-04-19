<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>

<html>
<head><title>Modify Users</title></head>
<body>
	<?php
	// input student
	// displays a form with all student fields
	if (strcmp($_POST['action'], 'input_student') == 0) {
		// fetches all available degrees
		$major = $_POST['major'];
		$degree_html = "<select name ='major'>";
		$query = "SELECT degree_name FROM degrees;";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$degree = $row['degree_name'];
			$selected = '';
			if (strcmp($major, $degree) == 0) {
				$selected = "selected='selected'";
			}
			$degree_html .= "<option value='$degree' $selected>
			$degree</option>";
		}
		$degree_html .= "</select><br><br>";
		echo "<h2>Enter Student Information</h2></br>";
		// outputs the form
		// hidden variables at the top pass information
		// back to this file, but this time with action 
		// being set to add_student
		$username = $_POST['username'];
		$password = $_POST['password'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$SSN = $_POST['SSN'];
		$address = $_POST['address'];
		$email = $_POST['email'];
		echo "<form method='post' action='user.php'>
			<p>Username<p><input type='text' name='username' value='$username'>
			<p>Password<p><input type='text' name='password' value='$password'>
			<p>First Name<p><input type='text' name='firstname' value='$firstname'>
			<p>Last Name<p><input type='text' name='lastname' value='$lastname'>
			<p>GWID is Generated<p><input type='hidden' name='randomGWID'>
			<p>SSN<p><input type='text' name='SSN' value='$SSN'>
			<p>Degree<p>$degree_html
			<p>Address<p><input type='text' name='address' value='$address'>
			<p>Email<p><input type='text' name='email' value='$email'>
			<input type='hidden' name='action' value='add_student'>
			<input type='submit' name='submit' value='Create User'>
			</form>";
	}
	
	// add student
	// adds a given student to the database
	if (strcmp($_POST['action'], 'add_student') == 0) {
		// gets the student from Post
		$username = $_POST['username'];
		$password = $_POST['password'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$gwid = $_POST['randomGWID'] ? $_POST['randomGWID'] : generateGWID();
		$SSN = $_POST['SSN'];
		$major = $_POST['major'];
		$address = $_POST['address'];
		$email = $_POST['email'];

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "<p>Invalid email format</p><br>";
			echo "<form method='post' action='user.php'>
			<input type='hidden' name='action' value='input_student'>
			<input type='hidden' name='username' value='$username'>
			<input type='hidden' name='password' value='$password'>
			<input type='hidden' name='firstname' value='$firstname'>
			<input type='hidden' name='lastname' value='$lastname'>
			<input type='hidden' name='SSN' value='$SSN'>
			<input type='hidden' name='major' value='$major'>
			<input type='hidden' name='address' value='$address'>
			<input type='hidden' name='email' value='$email'>
			<input type='submit' name='submit' value='Back'>
			</form>";
		} else {
			// give the student a login
			$login_query = "INSERT INTO login (username, password, role)
			VALUES ('$username', '$password', 'STUDENT');";
			// then can create the student
			$students_query .= "INSERT INTO students 
			(firstname, lastname, gwid, ssn, major, cleared, address, email, username)
			VALUES ('$firstname', '$lastname', '$gwid', '$SSN', '$major',
				0, '$address','$email', '$username');";
			$result1 = mysqli_query($conn, $login_query);
			$result2 = mysqli_query($conn, $students_query);
			// check result of adding a student (if there was a dupe ect)
			if (!$result1 || !$result2) {
				echo "<h2>Problem creating student</h2><br>";
				echo $login_query . "<br>";
				echo $students_query . "<br>";
			} else {
				echo "<p>Insert Successfuly</p>";
			}
			echo "<form method='post' action='admin.php'>
				<input type='submit' name='submit' value='Button'>
				</form";
		}
	}

	//generate GWID
	function generateGWID($length = 8) {
  	$characters = '0123456789';
  	$randomString = 'G';
  	for ($i = 0; $i < $length; $i++) {
    	$randomString .= $characters[rand(0, strlen($characters) - 1)];
  	}
  	return 'G' . $randomString;
	}

	// remove student
	// delete all information related to a student
	if (strcmp($_POST['action'], 'remove_student') == 0) {
		$gwid = $_POST['gwid'];
		// query form username of the student
		$username_query = "SELECT username FROM students
			WHERE gwid='$gwid'";
		$result = mysqli_query($conn, $username_query);
		$row = mysqli_fetch_assoc($result);
		$st_username = $row['username'];
		// first, delete the students grades
		$courses_query = "DELETE FROM course_status 
			WHERE gwid='$gwid'";
		// second, the link to the faculty advisor
		$advises_query = "DELETE FROM advises 
			WHERE gwid='$gwid'";
		// then, any pending applications
		$application_query = "DELETE FROM applications 
			WHERE gwid='$gwid'";
		// then the student himself/herself
		$students_query = "DELETE FROM students 
			WHERE gwid='$gwid';";
		// then the login for that student
		$login_query = "DELETE FROM login where username='$st_username';";
		$result1 = mysqli_query($conn, $courses_query);
		$result2 = mysqli_query($conn, $advises_query);
		$result3 = mysqli_query($conn, $application_query);
		$result4 = mysqli_query($conn, $students_query);
		$result5 = mysqli_query($conn, $login_query);
		// check if successful
		if ($result1 && $result2 && $result3 && $result4 && $result5) {
			echo "<p>Student Successfully Removed</p><br>";
		} else {
			echo "<p>Could not remove student</p><br>";
			echo $courses_query ."<br>";
			echo $advises_query ."<br>";
			echo $application_query ."<br>";
			echo $students_query ."<br>";
			echo $login_query ."<br>";
		}
		echo "<a href='admin.php'>Back</a>";
	}



	// Remove Faculty Button 
	if (strcmp($_POST['action'], 'remove_faculty')==0){

		$username = $_POST['username'];
		$fid = $_POST['fid'];


		$remove_query1 = "DELETE FROM advises 
		WHERE fid='$fid';";
		$remove_query2 = "DELETE FROM faculty 
		WHERE fid='$fid';";
		$remove_query3 = "DELETE FROM login
		WHERE username='$username';";

		$resultA = mysqli_query($conn, $remove_query1);
		$resultB = mysqli_query($conn, $remove_query2);
		$resultC = mysqli_query($conn, $remove_query3);
		if($resultA && $resultB && $resultC){
			echo "<p>Faculty successfully removed.</p><br>";
		}
		else {
			echo "<p>Could not remove faculty</p><br>";
			echo $remove_query1 . "<br>";
			echo $remove_query2 . "<br>";
			echo $remove_query3 . "<br>";
		}
		echo "<a href='admin.php'>Back</a>";

	}








	// Faculty Button Code Begins Here 
	// input faculty, similar to input_student
	if (strcmp($_POST['action'], 'input_faculty') == 0) {
		// Add email to form / db ?
		// <p>Email<p><input type='text' name='email'>
		echo "<h2>Enter Faculty Information.</h2><br>";
		echo "<form method='post' action='user.php'>
		<p>Username<p><input type='text' name='username'>
		<p>Password<p><input type='text' name='password'>
		<p>First Name<p><input type='text' name='firstname'>
		<p>Last Name<p><input type='text' name='lastname'>
		<p>Faculty ID is Generated<p><input type='hidden' name='randomGWID'>
		<p>SSN<p><input type='text' name='SSN'>
		<p>Address<p><input type='text' name='address'>
		<input type='hidden' name='action' value='add_faculty'>
		<input type='submit' name='submit' value='Create User'>
		</form>";
	}
	// add faculty, similar to add student
	if (strcmp($_POST['action'], 'add_faculty') == 0) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$fid = $_POST['randomGWID'] ? $_POST['randomGWID'] : generateGWID();
		$SSN = $_POST['SSN'];
		$address = $_POST['address'];
		$login_query_A = "INSERT INTO login (username, password, role)
		VALUES ('$username', '$password', 'FACULTY_ADVISOR');";
		$faculty_query .= "INSERT INTO faculty 
		(firstname, lastname, fid, SSN, address, username)
		VALUES ('$firstname', '$lastname', '$fid', '$SSN', $address', '$username');";
		$result_A = mysqli_query($conn, $login_query_A);
		$result_B = mysqli_query($conn, $faculty_query);
		if (!$result_A || !$result_B) {
			echo "<h2>Problem creating faculty.</h2><br>";
			echo $login_query_A . "<br>";
			echo $faculty_query . "<br>";
		} else {
			echo "<p>Insert Successfuly</p>";
		}
		echo "<a href='admin.php'>Back</a>";
	}

	// alumni
	//input alumni
	if (strcmp($_POST['action'], 'input_alumni') == 0) {
		$degree_html = "<select name ='degree_name'>";
		$query = "SELECT degree_name FROM degrees;";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$degree = $row['degree_name'];
			$degree_html .= "<option value='$degree'>
			$degree</option>";
		}
		$degree_html .= "</select><br><br>";
		echo "<h2>Enter Student Information</h2></br>";
		$username = $_POST['username'];
		$password = $_POST['password'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$gwid = $_POST['gwid'];
		$degree_name = $_POST['degree_name'];
		$year = $_POST['year'];
		$address = $_POST['address'];
		$email = $_POST['email'];

		echo "<form method='post' action='user.php'>
			<p>Username<p><input type='text' name='username' value='$username'>
			<p>Password<p><input type='text' name='password' value='$password'>
			<p>First Name<p><input type='text' name='firstname' value='$firstname'>
			<p>Last Name<p><input type='text' name='lastname' value='$lastname'>
			<p>GWID<p><input type='text' name='gwid' value='$gwid'>
			<p>Degree<p>$degree_html
			<p>Year</p><input type='text' name='year' value='$year'>
			<p>Address<p><input type='text' name='address' value='$address'>
			<p>Email<p><input type='text' name='email' value='$email'>
			<input type='hidden' name='action' value='add_alumni'>
			<input type='submit' name='submit' value='Create User'>
			</form>";
	}
	// add alumni
	if (strcmp($_POST['action'], 'add_alumni') == 0) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$gwid = $_POST['gwid'];
		$degree_name = $_POST['degree_name'];
		$year = $_POST['year'];
		$address = $_POST['address'];
		$email = $_POST['email'];
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "<p>Invalid email formatting</p><br>";
			echo "<form method='post' action='user.php'>
			<input type='hidden' name='action' value='input_alumni'>
			<input type='hidden' name='username' value='$username'>
			<input type='hidden' name='password' value='$password'>
			<input type='hidden' name='firstname' value='$firstname'>
			<input type='hidden' name='lastname' value='$lastname'>
			<input type='hidden' name='gwid' value='$gwid'>
			<input type='hidden' name='degree_name' value='$degree_name'>
			<input type='hidden' name='year' value='$year'>
			<input type='hidden' name='address' value='$address'>
			<input type='hidden' name='email' value='$email'>
			<input type='submit' name='submit' value='Back'>
			</form>";
		}
		else {
			$login_query = "INSERT INTO login (username, password, role)
			VALUES ('$username', '$password', 'ALUMNI');";
			$alumni_query .= "INSERT INTO alumni 
			(firstname, lastname, gwid, degree_name, year, address, email, username)
			VALUES ('$firstname', '$lastname', '$gwid', '$degree_name',
				$year, '$address','$email', '$username');";
			$result1 = mysqli_query($conn, $login_query);
			$result2 = mysqli_query($conn, $alumni_query);
			if (!$result1 || !$result2) {
				echo "<h2>Problem creating alumni</h2><br>";
				echo $login_query . "<br>";
				echo $alumni_query . "<br>";
			} else {
				echo "<p>Insert Successfuly</p>";
			}
			echo "<a href='admin.php'>Back</a>";
		}
	}
	//remove alumni
	if (strcmp($_POST['action'], 'remove_alumni') == 0) {
		$gwid = $_POST['gwid'];
		$username_query = "SELECT username FROM alumni
			WHERE gwid='$gwid'";
		$result = mysqli_query($conn, $username_query);
		$row = mysqli_fetch_assoc($result);
		$al_username = $row['username'];
		$courses_query = "DELETE FROM alumni_course_status 
			WHERE gwid='$gwid'";
		$students_query = "DELETE FROM alumni 
			WHERE gwid='$gwid';";
		$login_query = "DELETE FROM login where username='$al_username';";
		$result1 = mysqli_query($conn, $courses_query);
		$result2 = mysqli_query($conn, $students_query);
		$result3 = mysqli_query($conn, $login_query);
		if ($result1 && $result2 && $result3) {
			echo "<p>Alumni Successfully Removed</p><br>";
		} else {
			echo "<p>Could not remove student</p><br>";
			echo $courses_query ."<br>";
			echo $students_query ."<br>";
			echo $login_query ."<br>";
		}
		echo "<a href='admin.php'>Back</a>";
	}
	// degree
	// input degree
	if (strcmp($_POST['action'], 'input_degree') == 0) {
		$degree_html = "<select name ='major'>";
		$degree_html .= "<option value = '0'>-----</option>";
		$query = "SELECT degree_name FROM degrees;";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$degree = $row['degree_name'];
			$degree_html .= "<option value='$degree'>
			$degree</option>";
		}
		$degree_html .= "</select><br><br>";

		echo "<h2>Enter Degree Information.</h2><br>";
		echo "<form method='post' action='user.php'>
		<p>Degree Name<p><input type='text' name='degree_name'>
		<p>Core Course 1<p><input type='text' name='core1'>
		<p>Core Course 2<p><input type='text' name='core2'>
		<p>Core Course 3<p><input type='text' name='core3'>
		<input type='hidden' name='action' value='add_degree'>
		<input type='submit' name='submit' value='Add Degree'>
		</form>";

	}
	//add degree
	if (strcmp($_POST['action'], 'add_degree') == 0) {
		$degree_name = $_POST['degree_name'];
		$core1 = $_POST['core1'];
		$core2 = $_POST['core2'];
		$core3 = $_POST['core3'];


		$degree_query .= "INSERT INTO degrees 
		(degree_name, core1, core2, core3)
		VALUES ('$degree_name', '$core1', '$core2', '$core3');";

		$degree_result = mysqli_query($conn, $degree_query);
		if (!$degree_result) {
			echo "<h2>Problem adding degree.</h2><br>";
			echo $degree_query . "<br>";
		} else {
			echo "<p>Insert Successfully</p>";
		}
		echo "<a href='admin.php'>Back</a>";
	}

	//Edit Degree Requirements Code
	if (strcmp($_POST['action'], 'edit_degree_requirements') == 0) {
		$degree_html = "<select name ='major'>";
		$degree_html .= "<option value = '0'>-----</option>";
		$query = "SELECT degree_name FROM degrees;";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$degree = $row['degree_name'];
			$degree_html .= "<option value='$degree'>
			$degree</option>";
		}
		$degree_html .= "</select><br><br>";

		echo "<h2>Edit Degree Requirements.</h2><br>";
		echo "<form method='post' action='user.php'>
		<p>Degree Name<p><input type='text' name='degree_name'>
		<p>Core Course 1<p><input type='text' name='core1'>
		<p>Core Course 2<p><input type='text' name='core2'>
		<p>Core Course 3<p><input type='text' name='core3'>
		<input type='hidden' name='action' value='edit_degree'>
		<input type='submit' name='submit' value='Edit Degree'>
		</form>";

	}
	// edit degree
	if (strcmp($_POST['action'], 'edit_degree') == 0) {
		$degree_name = $_POST['degree_name'];
		$core1 = $_POST['core1'];
		$core2 = $_POST['core2'];
		$core3 = $_POST['core3'];


		$degree_query .= "UPDATE degrees 
		(degree_name, core1, core2, core3)
		VALUES ('$degree_name', '$core1', '$core2', '$core3');";

		$degree_result = mysqli_query($conn, $degree_query);
		if (!$degree_result) {
			echo "<h2>Problem editing requirement.</h2><br>";
			echo $degree_query . "<br>";
		} else {
			echo "<p>Updated Successfully</p>";
		}
		echo "<a href='admin.php'>Back</a>";
	}

	
	?>
</body>
</html>
