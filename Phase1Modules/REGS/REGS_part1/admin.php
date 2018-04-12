<?php
	session_start();
	if (strcmp($_SESSION["role"], "admin") != 0) {
		die("ACCESS DENIED");
	}

?>

<html>
<head>
	<title> Admin </title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
	<img src="gw_logo.png" alt="gw logo">
	<ul>
	  <form method="post" action="admin.php">
	    <li><input type="submit" class=fsSubmitButton name="transcript" value="View a Students Transcript"></li>
	    <li><input type="submit" class=fsSubmitButton name="grades" value="Enter a student's grades"></li>
		<li><input type="submit" class=fsSubmitButton name="add" value="Add a new user"></li>
		<li><input type="submit"  class=fsSubmitButton name="student_info" value="View Student Information"></li>
		<li><input type="submit" class=fsSubmitButton name="update_classes_input" value="Update Available Classes"></li>
		<li><input type="submit" class=fsSubmitButton name="reset" value="Reset Database"></li>
		<li><input type="submit" class=fsSubmitButton name="deactivate" value="Deactivate Account"></li>
		<li><input type="submit" class=fsSubmitButton name="change_role" value="Change Account Role"></li>
		<li><input type="submit" class=fsSubmitButton name="change_classes" value="Add or Remove Courses Offered"></li>
		<li style="float:right"><input type="submit" class=fsSubmitButton name="logout" value="logout" formaction="logout.php"><li>
	  </form>
	</ul>

<?php
	/* variables for determining which page to display */
	$transcript = $_POST["transcript"];
	$grades = $_POST["grades"];
	$transcript_search = $_POST["transcript_search"];
	$transcriptName_search = $_POST["transcriptName_search"];
	$grade_search = $_POST["grade_search"];
	$grade_Nsearch = $_POST["grade_Nsearch"];
	$change_grade = $_POST["change_grade"];
	$add = $_POST["add"];
	$info_prompt = $_POST["info_prompt"];
	$info = $_POST["info"];
	$student_info = $_POST["student_info"];
	$info_search = $_POST["info_search"];
	$update_classes_input = $_POST["update_classes_input"];
	$update_classes = $_POST["update_classes"];
	$reset = $_POST["reset"];
	$deactivate_prompt = $_POST["deactivate"];
	$deactivate = $_POST["do_deactivate"];
	$change_role = $_POST["change_role"];
	$do_change_role = $_POST["do_change_role"];
	$change_classes = $_POST["change_classes"];
	$add_class_info = $_POST["add_class_info"];
	$add_class = $_POST["add_class"];
	$remove_class = $_POST["remove_class"];

	$fname = $_POST["fname"];
	$lname = $_POST["lname"];

	$student_id = $_POST["student_id"];
	$student_name = $_POST["student_name"];

	$new_class_crn = $_POST["new_class_crn"];
	$remove_class_crn = $_POST["remove_class_crn"];

	/* login credentials */
	$servername = "localhost";
	$username = "team3";
	$password = "e9Yez5FL";
	$dbname = "team3";

	/*connect to database */
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	/* display for adding new user*/
	if($add) {
		echo "<h2 style='text-align:center'> Add New User </h2>";
		echo "<p> Enter user info then hit enter </p>";
		echo "<p> Valid user types: admin, student, professor, gs</p>";
		echo "<form method='post' action='admin.php'>";
		echo    "<label for='username'>Username: </label>";
		echo    "<input type='text' id='username' name='username' /> <br/>";
		echo    "<label for='password'>Password: </label>";
		echo    "<input type='text' id='password' name='password' /> <br/>";
		echo    "<label for='type'>Type of user: </label>";
		echo    "<input type='text' id='type' name='type' placeholder='' /> <br/>";
		echo    "<input type='submit' value='Enter' name='info_prompt' />";
		echo "</form>";
	}

	/* display for after entering a new user
		* prompts for user information */
	if($info_prompt) {
		$username = $_POST["username"];
		$password = $_POST["password"];
		//$hash = password_hash($password, PASSWORD_BCRYPT);
		$type = $_POST["type"];

		/* error check type */
		if (!(strcmp($type, "student") == 0 || strcmp($type, "professor") == 0 || strcmp($type, "gs") == 0 || strcmp($type, "admin") == 0)) {
			die("Invalid user type\n");
		}
		$query = "INSERT INTO users
			VALUES('".$username."', '".$password."', '".$type."');";
		$result = mysqli_query($conn, $query);

		if (!$result) {
			echo "Username Taken <br/>";
			echo "Try again <br/>";
		}
		else {

		/*store type of user and username as a session variables */

			$_SESSION["newType"] = $type;
			$_SESSION["newUser"] = $username;

			/* ask for new user information to update required table */
			if(strcmp($type, student) == 0) {
				/* ask for necessary info in student table
				 * then redirect and update */
				echo "<form method='post' action='admin.php'>";
				echo    "<label for='first'>First Name: </label>";
				echo    "<input type='text' id='first' name='first' /> <br/>";
				echo    "<label for='last'>Last Name: </label>";
				echo    "<input type='text' id='last' name='last' /> <br/>";
				echo    "<label for='street'>Street: </label>";
				echo    "<input type='text' id='street' name='street' /> <br/>";
				echo    "<label for='city'>City: </label>";
				echo    "<input type='text' id='city' name='city' /> <br/>";
				echo    "<label for='email'>Email: </label>";
				echo    "<input type='text' id='email' name='email' /> <br/>";
				echo    "<label for='degree'>Degree: </label>";
				echo    "<input type='text' id='degree' name='degree' /> <br/>";
				echo    "<input type='submit' value='Continue' name='info' /> <br/>";
				echo "</form>";
			}
			if(strcmp($type, professor) == 0) {
				/* ask for necessary info in professor table
				 * then redirect and update */

				echo "<form method='post' action='admin.php'>";
				echo    "<label for='name'>Name: </label>";
				echo    "<input type='text' id='name' name='name' /> <br/>";
				echo    "<label for='email'>Email: </label>";
				echo    "<input type='text' id='email' name='email' /> <br/>";
				echo    "<input type='submit' value='Continue' name='info' /> <br/>";
				echo "</form>";

			}
			if(strcmp($type, gs) == 0) {
				/* ask for necessary info in gs table
				* then redirect and update */

				echo "No more info necessary";
			}
			if(strcmp($type, admin) == 0) {
				/* ask for necessary info in student table
				* then redirect and update */

				echo "No more info necessary";
			}
		}
	}

	/* handle updating student and professor tables */
	if($info) {
		/* check type of user
		 * perform appropriate table update */
		if(strcmp($_SESSION["newType"], "student") == 0) {
			$first = $_POST["first"];
			$last = $_POST["last"];
			$street = $_POST["street"];
			$city = $_POST["city"];
			$email = $_POST["email"];
			$degree = $_POST["degree"];
			$uname = $_SESSION["newUser"];

			/* calculate new student id */
			$query = "SELECT * FROM students;";
			$result = mysqli_query($conn, $query);
			$id = mysqli_num_rows($result) + 500000001;

			$query = "INSERT INTO students
				VALUES ('".$id."', '".$first."', '".$last."', '".$street."', '".$city."', '".$email."', '".$degree."', '".$uname."');";
			$result = mysqli_query($conn, $query);
			if($result) {
				echo "Successfully added student";
			} else {
				$query = "DELETE FROM users WHERE username == ".$uname.";";
				mysqli_query($conn, $query);

				echo "Failed to add user";
			}
		}

		else if(strcmp($_SESSION["newType"], "professor") == 0) {
			$name = $_POST["name"];
			$email = $_POST["email"];
			$uname = $_SESSION["newUser"];

			/* calculate new prof id */
			$query = "SELECT * FROM professors;";
			$result = mysqli_query($conn, $query);
			$id = mysqli_num_rows($result) + 1;

			$query = "INSERT INTO professors
				VALUES ('".$id."', '".$name."', '".$email."', '".$uname."');";
			$result = mysqli_query($conn, $query);
			if($result) {
				echo "Successfully added professor";
			} else {
				$query = "DELETE FROM users WHERE username == ".$uname.";";
				mysqli_query($conn, $query);

				echo "Failed to add user";
			}
		}
		else {
			echo "Sucessfully added user";
		}
	}

	/* display page to search for a student's transcript */
	if($transcript){
		echo '<form method="post" action="admin.php">';
		echo '<h4>Enter a Student Name:</h4>';
		echo 'First Name: <input type="text" name="fname"><br>';
		echo 'Last Name: <input type="text" name="lname"><br>';
		echo '<input type="submit" name="transcriptName_search" value="Search">';
		echo '<h4>Enter a Student ID:</h4> <input type="text" name="student_id"><br>';
		echo '<input type="submit" name="transcript_search" value="Search">';
		echo '</form>';
	}

	/* display student's transcript using the name as the input*/
	if($transcriptName_search){
		/* get and display student name*/
		$query = "SELECT s.fname, s.lname
			FROM students s
			WHERE s.fname = '$fname' and s.lname = '$lname';";

		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);

		$user_exists = 0;
		if (mysqli_num_rows($result) > 0){
			echo "<h2>".$row["fname"]." ".$row["lname"]."</h2>";
			$user_exists = 1;
		}

		/* get transcript information */
		$query = "SELECT t.dept, t.cid, c.cHours, t.grade, t.year, t.semester
			FROM transcripts t, courses c, students s
			WHERE t.cid = c.cid AND t.dept = c.dept AND
			s.fname = '$fname' AND s.lname = '$lname' AND t.SID = s.ID
			ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($conn, $query);

		$total_credits = 0;
		$weight = 0;
		$sum = 0;

		/* display transcript information */
		if (mysqli_num_rows($result) > 0){
			echo "<table>";
			$cur_year = ""; //track current year
			$cur_sem = ""; //track current semester
			while ($row = mysqli_fetch_assoc($result)){
				if($cur_year != $row["year"] || $cur_sem != $row["semester"]){
					echo "</table><br><table>";
					echo "<tr><th colspan=2>Course</th><th>Credits</th><th>Grade</th><th>Semester</th><th>Year</th></tr>";
					$cur_year = $row["year"];
					$cur_sem = $row["semester"];
				}
				echo "<tr>";

				echo "<td>".$row["dept"]."</td>";
				echo "<td>".$row["cid"]."</td>";
				echo "<td>".$row["cHours"]."</td>";
				echo "<td>".$row["grade"]."</td>";
				echo "<td>".$row["semester"]."</td>";
				echo "<td>".$row["year"]."</td>";

				echo "</tr>";

				/* gpa calculation */
				$weight = $row["cHours"];
				if (strcmp($row["grade"], "IP") != 0) {
					$total_credits = $total_credits + $weight;
				}

				if (strcmp($row["grade"], "A") == 0) {
					$sum = $sum + ($weight * 4.0);
				} else if (strcmp($row["grade"], "A-") == 0) {
					$sum = $sum + ($weight * 3.7);
				} else if (strcmp($row["grade"], "B+") == 0) {
					$sum = $sum + ($weight * 3.3);
				} else if (strcmp($row["grade"], "B") == 0) {
					$sum = $sum + ($weight * 3.0);
				} else if (strcmp($row["grade"], "B-") == 0) {
					$sum = $sum + ($weight * 2.7);
				} else if (strcmp($row["grade"], "C+") == 0) {
					$sum = $sum + ($weight * 2.3);
				} else if (strcmp($row["grade"], "C") == 0) {
					$sum = $sum + ($weight * 2.0);
				} else if (strcmp($row["grade"], "F") == 0) {
					$sum = $sum + ($weight * 0.0);
				}
			}
			echo "</table>";

			$gpa = $sum / $total_credits;

			echo "<br/>";
			echo "<br/>";
			echo "<br/>";
			echo "<h4> GPA: " . $gpa;
		}
		else if ($user_exists == 1){
			echo "This student has not taken any classes yet";
		}
		else {
			echo "Sorry this user does not exist";
		}
	}

	/* display student's transcript */
	if($transcript_search){
		/* variables for displaying transcript */
		$student_id = $_POST["student_id"];

		/* get and display student name*/
		$query = "SELECT s.fname, s.lname
			FROM students s
			WHERE s.id = '$student_id';";

		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);

		$user_exists = 0;
		if (mysqli_num_rows($result) > 0){
			echo "<h2>".$row["fname"]." ".$row["lname"]."</h2>";
			$user_exists = 1;
		}

		/* get transcript information */
		$query = "SELECT t.dept, t.cid, c.cHours, t.grade, t.year, t.semester
			FROM transcripts t, courses c
			WHERE t.cid = c.cid AND t.dept = c.dept AND
			t.sid = '$student_id'
			ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($conn, $query);

		$total_credits = 0;
		$weight = 0;
		$sum = 0;

		/* display transcript information */
		if (mysqli_num_rows($result) > 0){
			echo "<table>";
			$cur_year = ""; //track current year
			$cur_sem = ""; //track current semester
			while ($row = mysqli_fetch_assoc($result)){
				if($cur_year != $row["year"] || $cur_sem != $row["semester"]){
				echo "</table><br><table>";
				echo "<tr><th colspan=2>Course</th><th>Credits</th><th>Grade</th><th>Semester</th><th>Year</th></tr>";
				$cur_year = $row["year"];
				$cur_sem = $row["semester"];
				}
				echo "<tr>";

				echo "<td>".$row["dept"]."</td>";
				echo "<td>".$row["cid"]."</td>";
				echo "<td>".$row["cHours"]."</td>";
				echo "<td>".$row["grade"]."</td>";
				echo "<td>".$row["semester"]."</td>";
				echo "<td>".$row["year"]."</td>";

				echo "</tr>";

				/* gpa calculation */
				$weight = $row["cHours"];
				if (strcmp($row["grade"], "IP") != 0) {
					$total_credits = $total_credits + $weight;
				}

				if (strcmp($row["grade"], "A") == 0) {
					$sum = $sum + ($weight * 4.0);
				} else if (strcmp($row["grade"], "A-") == 0) {
					$sum = $sum + ($weight * 3.7);
				} else if (strcmp($row["grade"], "B+") == 0) {
					$sum = $sum + ($weight * 3.3);
				} else if (strcmp($row["grade"], "B") == 0) {
					$sum = $sum + ($weight * 3.0);
				} else if (strcmp($row["grade"], "B-") == 0) {
					$sum = $sum + ($weight * 2.7);
				} else if (strcmp($row["grade"], "C+") == 0) {
					$sum = $sum + ($weight * 2.3);
				} else if (strcmp($row["grade"], "C") == 0) {
					$sum = $sum + ($weight * 2.0);
				} else if (strcmp($row["grade"], "F") == 0) {
					$sum = $sum + ($weight * 0.0);
				}
			}
			echo "</table>";

			$gpa = $sum / $total_credits;

			echo "<br/>";
			echo "<br/>";
			echo "<br/>";
			echo "<h4> GPA: " . $gpa;
		}
		else if ($user_exists == 1){
			echo "This student has not taken any classes yet";
		}
		else {
			echo "Sorry this isn't a valid student ID";
		}
    }

	/* display page to search for a student to change grades */
	if($grades){
		echo '<form method="post" action="admin.php">';
		echo '<h4>Enter a Student Name:</h4>';
		echo 'First Name: <input type="text" name="fname"><br>';
		echo 'Last Name: <input type="text" name="lname"><br>';
		echo '<input type="submit" name="grade_Nsearch" value="Search">';
		echo '<h4>Enter a Student ID:</h4> <input type="text" name="student_id"><br>';
		echo '<input type="submit" name="grade_search" value="Search">';
		echo '</form>';
	}

	if($grade_Nsearch){
		/* get and display student name*/
		$query = "SELECT s.fname, s.lname
			FROM students s
			WHERE s.fname = '$fname' AND s.lname = '$lname'";

		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);

		$user_exists = 0;
		if (mysqli_num_rows($result) > 0){
			echo "<h2>".$row["fname"]." ".$row["lname"]."</h2>";
			$user_exists = 1;
		}

		/* get student grade information */
		$query = "SELECT t.sid, t.dept, t.cid, t.grade, t.semester, t.year, t.sectionNum
			FROM students s, transcripts t
			WHERE s.id=t.sid AND s.fname = '$fname'
			ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($conn, $query);

		/* display student's current grade information with option to change */
		if (mysqli_num_rows($result) > 0){
			echo "<table>";
			echo "<tr><th colspan=2>Course</th><th>Section</th><th>Semester</th><th>Year</th><th>Grade</th><th></th></tr>";
			while ($row = mysqli_fetch_assoc($result)){
				echo "<tr>";

				echo "<td>".$row["dept"]."</td><td>".$row["cid"]."</td><td>".$row["sectionNum"]."</td><td>".$row["semester"]."</td><td>".$row["year"]."</td>";
				echo "<td><form method='post' action='admin.php'>";
				echo "<input type='text' name='new_grade' value=".$row["grade"].">";
				echo "</td>";
				echo "<td><input type='submit' name='change_grade' value='Change'></td>";

				/* pass through info needed to change grade */
				echo "<input type='hidden' name='sid' value=".$row["sid"].">";
				echo "<input type='hidden' name='dept' value=".$row["dept"].">";
				echo "<input type='hidden' name='cid' value=".$row["cid"].">";
				echo "<input type='hidden' name='semester' value=".$row["semester"].">";
				echo "<input type='hidden' name='year' value=".$row["year"]."></form>";

				echo "</tr>";
			}
			echo "</table>";

			$_SESSION["reload"] = 0;
			$_SESSION["sid"] = "nobody";
		}
		else if ($user_exists == 1){
			echo "This student has not taken any classes yet";
		}
		else {
			echo "Sorry that user does not exist";
		}
	}


	/* change students grade and display confirmation */
	if($change_grade){
		/* variables for changing students grades */
		$new_grade = $_POST["new_grade"];
		$sid = $_POST["sid"];
		$dept = $_POST["dept"];
		$cid = $_POST["cid"];
		$semester = $_POST["semester"];
		$year = $_POST["year"];

		$query = "UPDATE transcripts
			SET grade = '$new_grade'
			WHERE sid = '$sid' AND dept = '$dept' AND cid = '$cid' AND semester = '$semester' AND year = '$year';";


		if(strcmp($new_grade, "A") == 0 || strcmp($new_grade, "A-"    ) == 0 || strcmp($new_grade, "B+") == 0 || strcmp($new_grade, "B") == 0 ||     strcmp($new_grade, "B-") == 0 || strcmp($new_grade, "C+") == 0 || strcmp(    $new_grade, "C") == 0 || strcmp($new_grade, "F") == 0) {

			$result = mysqli_query($conn, $query);
		} else {
			echo "Invalid Grade <br/>";
		}

		$_SESSION["reload"] = 1;
		$_SESSION["sid"] = $sid;
	}


	/* display page change a students grades */
	if($grade_search || $_SESSION["reload"] == 1){

		if ($_SESSION["reload"] == 1) {
			$student_id = $_SESSION["sid"];
		}

		/* get and display student name*/
		$query = "SELECT s.fname, s.lname
			FROM students s
			WHERE s.id = '$student_id';";

		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);

		$user_exists = 0;
		if (mysqli_num_rows($result) > 0){
			echo "<h2>".$row["fname"]." ".$row["lname"]."</h2>";
			$user_exists = 1;
		}

		/* get student grade information */
		$query = "SELECT t.sid, t.dept, t.cid, t.grade, t.semester, t.year, t.sectionNum
			FROM students s, transcripts t
			WHERE s.id=t.sid AND t.sid = '$student_id'
			ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($conn, $query);

		/* display student's current grade information with option to change */
		if (mysqli_num_rows($result) > 0){
			echo "<table>";
			echo "<tr><th colspan=2>Course</th><th>Section</th><th>Semester</th><th>Year</th><th>Grade</th><th></th></tr>";
			while ($row = mysqli_fetch_assoc($result)){
				echo "<tr>";

				echo "<td>".$row["dept"]."</td><td>".$row["cid"]."</td><td>".$row["sectionNum"]."</td><td>".$row["semester"]."</td><td>".$row["year"]."</td>";
				echo "<td><form method='post' action='admin.php'>";
				echo "<input type='text' name='new_grade' value=".$row["grade"].">";
				echo "</td>";
				echo "<td><input type='submit' name='change_grade' value='Change'></td>";

				/* pass through info needed to change grade */
				echo "<input type='hidden' name='sid' value=".$row["sid"].">";
				echo "<input type='hidden' name='dept' value=".$row["dept"].">";
				echo "<input type='hidden' name='cid' value=".$row["cid"].">";
				echo "<input type='hidden' name='semester' value=".$row["semester"].">";
				echo "<input type='hidden' name='year' value=".$row["year"]."></form>";

				echo "</tr>";
			}
			echo "</table>";

			$_SESSION["reload"] = 0;
			$_SESSION["reload"] = "nobody";
		}
		else if ($user_exists == 1){
			echo "This student has not taken any classes yet";
		}
		else {
			echo "Sorry, this is not a valid ID";
		}
	}


	/* search for student information */
	if($student_info){
		echo '<form method="post" action="admin.php">';
		echo '<h4>Enter a Student  Name:</h4>';
		echo 'First Name: <input type="text" name="fname"><br>';
		echo 'Last Name: <input type="text" name="lname"><br>';
		echo '<input type="submit" name="info_search" value="Search">';
		echo '</form>';
	}

	/* display student infromation */
	if($info_search){
		$fname = $_POST["fname"];
		$lname = $_POST["lname"];

		$query = "SELECT *
			FROM students s
			WHERE s.fname = '$fname' AND s.lname = '$lname';";

		$result = mysqli_query($conn, $query);

		/* display student's current grade information with option to change */
		if (mysqli_num_rows($result) > 0){
			echo "<br><br>";
			echo "<table>";
			echo "<tr><th>ID</th><th colspan=2>Name</th><th colspan=2>Address</th><th>Email</th><th>Degree</th><th>Username</th></tr>";
			while ($row = mysqli_fetch_assoc($result)){
				echo "<tr>";

				echo "<td>".$row["ID"]."</td>";
				echo "<td>".$row["fname"]."</td>";
				echo "<td>".$row["lname"]."</td>";
				echo "<td>".$row["street"]."</td>";
				echo "<td>".$row["city"]."</td>";
				echo "<td>".$row["email"]."</td>";
				echo "<td>".$row["degree"]."</td>";
				echo "<td>".$row["username"]."</td>";

				echo "</tr>";
			}
			echo "</table>";
		}
		else{
			echo "User does not exist";
		}

	}

	if($update_classes_input){
		echo '<form method="post" action="admin.php">';
		echo '<h4>Enter the new Semester</h4>';
		echo "<input type='radio' name='new_semester' value='fall'>Fall";
		echo "<input type='radio' name='new_semester' value='spring'>Spring <br/>";
		echo '<h4>Enter the new Year</h4> <input type="text" name="new_year"><br><br>';
		echo '<input type="submit" name="update_classes" value="Update">';
		echo '</form>';
	}

	if($update_classes){
		$cur_semester;
		$cur_year;

		$query = "SELECT c.year, c.semester
					FROM courses c;";

		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_assoc($result);
			$cur_semester = $row["semester"];
			$cur_year = $row["year"];
		}

		/* store the new semester and year */
		$new_semester = $_POST["new_semester"];
  	  	$new_year = $_POST["new_year"];
		$valid = 0;
		if(strcmp($cur_semester, "fall") == 0 && is_numeric($new_year) &&
			strcmp($cur_semester, $new_semester) != 0 && (int)$new_year > (int)$cur_year){
			$valid = 1;
		}
		if(strcmp($cur_semester, "spring") == 0 && is_numeric($new_year) &&
			strcmp($cur_semester, $new_semester) != 0 && (int)$new_year == (int)$cur_year){
			$valid = 1;
		}

		if($valid == 1){

			$query = "UPDATE courses
                  	  SET semester = '$new_semester', year = '$new_year';";

        	$result = mysqli_query($conn, $query);
			echo "<h3>Successfully updated courses</h3>";
		}
		else{
			echo "<h3>Invalid new semester or year please try again</h3>";
		}
	}

	/* reset database */
	if($reset){
		$query = file_get_contents("database_setup.sql");
		$result = mysqli_multi_query($conn, $query);
	    	echo "<h3>Successfully reset database</h3>";
	}

	/* deactivate account */
	if($deactivate_prompt) {
		echo "<p> Enter username to be deactivated </p>";
		echo "<form method='post' action='admin.php'>";
		echo    "<label for='deac_user'>Deactivate: </label>";
		echo    "<input type='text' id='deac_user' name='deac_user'/> <br/>";
		echo    "<input type='submit' value='Enter' name='do_deactivate' />";
		echo "</form>";
	}

	if($deactivate) {
		$deac_user = $_POST["deac_user"];

		/* check if user exists */
		$query = "SELECT * FROM users WHERE username = '".$deac_user."';";
		$result = mysqli_query($conn, $query);
		$exists = mysqli_num_rows($result);    /* 1 if exists, 0 if otherwise */
		if($exists == 0) {
			echo "User does not exist";
		} else {
			$query = "UPDATE users SET role = 'inactive' WHERE username = '".$deac_user."';";
			$result = mysqli_query($conn, $query);

			if($result) {
				echo "Successfully deactivated account";
			} else {
				echo "Failed to deactivate user";
			}
		}
	}

	if($change_role) {
		echo "<p> Enter username and new role </p>";
		echo "<form method='post' action='admin.php'>";
		echo    "<label for='change_role_user'>Username: </label>";
		echo    "<input type='text' id='change_role_user' name='change_role_user'/> <br/>";
		echo    "<label for='new_role'>New Role: </label>";
		echo    "<input type='text' id='new_role' name='new_role'/> <br/>";
		echo    "<input type='submit' value='Enter' name='do_change_role' />";
		echo "</form>";
	}

	if($do_change_role) {
		$change_role_user = $_POST["change_role_user"];
		$new_role = $_POST["new_role"];

		if (strcmp($new_role, "admin") == 0 || strcmp($new_role, "gs") == 0 || strcmp($new_role, "professor") == 0 || strcmp($new_role, "student") == 0) {
			$query = "UPDATE users SET role = '".$new_role."' WHERE username = '".$change_role_user."';";
			$result = mysqli_query($conn, $query);

			if ($result) {
				echo "<br/>";
				echo "Successfully changed user role";
			} else {
				echo "<br/>";
				echo "Failed to change user role";
			}
		} else {
			echo "<br/>";
			echo "Invalid user type";
		}
	}
	if($change_classes){
		echo '<form method="post" action="admin.php">';
		echo '<h4>Enter CRN to add class</h4>';
		echo '<input type="text" name="new_class_crn"><br><br>';
		echo '<input type="submit" name="add_class_info" value="Add">';

		echo '<h4>Enter CRN to remove class</h4>';
		echo '<input type="text" name="remove_class_crn"><br><br>';
		echo '<input type="submit" name="remove_class" value="Remove">';
		echo '</form>';

		$query = "SELECT c.crn, c.dept, c.cid, c.sectionNum, p.name, c.year, c.semester, c.cHours, c.day, c.classTime
			FROM courses c, professors p
			WHERE c.profID = p.id
			ORDER BY c.crn;";
		$result = mysqli_query($conn, $query);

		echo "<h2> Current Classes </h2>";

		if (mysqli_num_rows($result) > 0) {
			echo "<table>";
			echo "<tr><th>CRN</th><th colspan=2>Course</th><th>Section</th><th>Professor</th><th>Year</th><th>Semester</th><th>Credits</th><th>Day</th><th>Time</th><th>Prereq1 CRN</th><th>Prereq2 CRN</th>";

			while($row = mysqli_fetch_assoc($result)) {

				echo "<tr>";
				echo  "<td>".$row["crn"]."</td><td>".$row["dept"]."</td><td>".$row["cid"]."</td><td>".$row["sectionNum"]."</td><td>".$row["name"]."</td><td>".$row["year"]."</td><td>".$row["semester"]."</td><td>".$row["cHours"]."</td><td>".$row["day"]."</td><td>".$row["classTime"]."</td>";

				/* get any prereqs */
				$query = "SELECT * FROM prereq1
					WHERE crn = '".$row["crn"]."';";
				$prereq = mysqli_query($conn, $query);
				$prereqCRN = mysqli_fetch_assoc($prereq);

				$query = "SELECT * FROM prereq2
					WHERE crn = '".$row["crn"]."';";
				$prereq2 = mysqli_query($conn, $query);
				$prereq2CRN = mysqli_fetch_assoc($prereq2);

				echo "<td>".$prereqCRN["preCRN"]."</td><td>".$prereq2CRN["preCRN"]."</td>";

				echo "</tr>";
			}
			echo "</table>";

		}
	}

	if($remove_class){
		$query = "DELETE FROM prereq1
					WHERE '$remove_class_crn' = crn;";
		$result = mysqli_query($conn, $query);

		$query = "DELETE FROM prereq2
					WHERE '$remove_class_crn' = crn;";
		$result = mysqli_query($conn, $query);

		$query = "DELETE FROM courses
					WHERE '$remove_class_crn' = crn;";
		$result = mysqli_query($conn, $query);
		if(mysqli_affected_rows($conn) > 0){
			echo "<h4>Class Removed</h4>";
		}
		else{
			echo "<h4>Invalid CRN</h4>";
		}
	}

	if($add_class_info){
		$query = "SELECT crn
					FROM courses
					WHERE $new_class_crn = crn;";
		$result = mysqli_query($conn, $query);

		if (!is_numeric($new_class_crn)){
			echo "<h4>Invalid CRN</h4>";
		}
		else if (mysqli_num_rows($result) > 0){
			echo "<h4>CRN already in use</h4>";
		}
		else{
			echo '<form method="post" action="admin.php">';
			echo '<h4>Input Class Info</h4>';

			echo '<label for=new_class_crn>CRN: </label>';
			echo "<input type=text name=new_class_crn value='$new_class_crn'><br>";

			echo '<label for=new_class_dept>Department: </label>';
			echo '<input type="text" name="new_class_dept"><br>';

			echo '<label for=new_class_cid>CID: </label>';
			echo '<input type="text" name="new_class_cid"><br>';

			echo '<label for=new_class_pid>Professor ID: </label>';
			echo '<input type="text" name="new_class_pid"><br>';

			echo '<label for=new_class_section>Section Number: </label>';
			echo '<input type="text" name="new_class_section"><br>';

			echo '<label for=new_class_credits>Credit Hours: </label>';
			echo '<input type="text" name="new_class_credits"><br>';

			echo '<label for=new_class_day>Day of the Week: </label>';
			echo '<input type="text" name="new_class_day"><br>';

			echo '<label for=new_class_time>Time (start time, just the hour, 24 hour format): </label>';
			echo '<input type="text" name="new_class_time"><br>';

			echo '<label for=new_class_prereq1>Prereq1 (use crn, if none leve blank): </label>';
			echo '<input type="text" name="new_class_prereq1"><br>';

			echo '<label for=new_class_prereq2>Prereq2 (use crn, if none leve blank): </label>';
			echo '<input type="text" name="new_class_prereq2"><br>';

			echo '<input type="submit" name="add_class" value="Add">';
			echo '</form>';
		}
	}

	if($add_class){
		$new_crn = $_POST["new_class_crn"];
		$new_dept = $_POST["new_class_dept"];
		$new_cid = $_POST["new_class_cid"];
		$new_pid = $_POST["new_class_pid"];
		$new_section = $_POST["new_class_section"];
		$new_credits = $_POST["new_class_credits"];
		$new_day = $_POST["new_class_day"];
		$new_time = $_POST["new_class_time"];
		$new_prereq1 = $_POST["new_class_prereq1"];
		$new_prereq2 = $_POST["new_class_prereq2"];
		$new_year = "";
		$new_semester = "";

		$valid = 1;
		/* check department */
		$new_dept = strtoupper($new_dept);
		if(strcmp($new_dept, "CS") != 0 && strcmp($new_dept, "EE") != 0 && strcmp($new_dept, "MATH") != 0){
			echo "Invalid Department<br />";
			$valid = 0;
		}

		/* check cid */
		if($valid == 0){
			echo "Can't check CID because of invalid department<br />";
		}
		else if (!is_numeric($new_cid)){
			echo "Invalid CID<br />";
			$valid = 0;
		}

		/* check section */
		if($valid == 0){
			echo "Can't check Section because of invalid department or CID<br />";
		}
		else if (!is_numeric($new_section)){
			echo "Invalid Section<br />";
		}
		else{
			$query = "SELECT c.dept, c.cid, c.sectionNum
						FROM courses c
						WHERE c.dept = '$new_dept' AND c.cid = '$new_cid' AND c.sectionNum = '$new_section';";
			$result = mysqli_query($conn, $query);

			if (mysqli_num_rows($result) > 0){
				echo "Duplicate Course<br />";
				$valid = 0;
			}
		}

		/* check pid */
		$query = "SELECT id
					FROM professors
					WHERE $new_pid = id;";
		$result = mysqli_query($conn, $query);

		if (mysqli_num_rows($result) <= 0){
			echo "Invalid Professor ID<br />";
			$valid = 0;
		}

		/* check credits */
		if(!is_numeric($new_credits) || $new_credits < 0 || $new_credits > 3){
			echo "Invalid Credits<br />";
			$valid = 0;
		}

		/* check day */
		$new_day = strtolower($new_day);
		if(strcmp($new_day, "monday") == 0 || strcmp($new_day, "m") == 0){
			$new_day = "M";
		}
		else if(strcmp($new_day, "tuesday") == 0 || strcmp($new_day, "t") == 0){
			$new_day = "T";
		}
		else if(strcmp($new_day, "wednesday") == 0 || strcmp($new_day, "w") == 0){
			$new_day = "W";
		}
		else if(strcmp($new_day, "thursday") == 0 || strcmp($new_day, "r") == 0){
			$new_day = "R";
		}
		else if(strcmp($new_day, "friday") == 0 || strcmp($new_day, "f") == 0){
			$new_day = "F";
		}
		else{
			echo "Invalid Day<br />";
			$valid = 0;
		}

		/* check time */
		if(is_numeric($new_time) && $new_time > 0 && $new_time < 24){
			$new_time = $new_time . ":00:00";
		}
		else{
			echo "Invalid Time<br />";
			$valid = 0;
		}

		/* if input is valid add to database */
		if($valid == 1){
			/* get current semester and year */
			$query = "SELECT c.year, c.semester
						FROM courses c
						LIMIT 1;";
			$result = mysqli_query($conn, $query);

			$row = mysqli_fetch_assoc($result);

			$new_year = $row["year"];
			$new_semester = $row["semester"];

			/* add class to database */
			$query = "INSERT INTO courses
						VALUES ('$new_crn', '$new_cid', '$new_dept', '$new_pid',
							 	'$new_year', '$new_semester', '$new_section',
								'$new_credits', '$new_day', '$new_time');";
			$result = mysqli_query($conn, $query);
			if(mysqli_affected_rows($conn) > 0){
				echo "<h4>Class Added</h4>";
			}
			else{
				echo "<h4>Query error</h4>";
			}

			/* check prereq1 */
			if($new_prereq1 != NULL){
				$query = "SELECT cid
							FROM courses
							WHERE crn = '$new_prereq1';";
				$result = mysqli_query($conn, $query);

				if (mysqli_num_rows($result) <= 0){
					echo "Invalid prereq1 CRN<br />";
				}
				else{
					$query = "INSERT INTO prereq1
								VALUES ('$new_crn', '$new_prereq1');";
					$result = mysqli_query($conn, $query);
				}
			}

			/* check prereq1 */
			if($new_prereq2 != NULL){
				$query = "SELECT cid
							FROM courses
							WHERE crn = '$new_prereq2';";
				$result = mysqli_query($conn, $query);

				$query2 = "SELECT preCRN
							FROM prereq1
							WHERE preCRN = '$new_prereq2' AND CRN = '$new_crn';";
				$result2 = mysqli_query($conn, $query2);

				if (mysqli_num_rows($result) <= 0){
					echo "Invalid prereq2 CRN<br />";
				}
				else if(mysqli_num_rows($result) > 0){
					echo "Duplicate Prereq 2<br />";
				}
				else{
					$query = "INSERT INTO prereq2
								VALUES ('$new_crn', '$new_prereq2');";
					$result = mysqli_query($conn, $query);
				}
			}
		}
		else{
			echo "Try Again<br />";
		}

	}

	mysqli_close($conn);
?>


</body>
</html>
