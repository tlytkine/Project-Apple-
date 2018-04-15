<?php
	session_start();
	if (strcmp($_SESSION["role"], "professor") != 0) {
		die("ACCESS DENIED");
	}
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<img src="gw_logo.png" alt="gw logo">
<ul>
	<form method="post" action="prof.php">
		<li><input type="submit" class=fsSubmitButton name="transcript" value="View a Students Transcript"></li>
		<li><input type="submit" class=fsSubmitButton name="grades" value="Enter a student's grades"></li>
		<li><input type="submit" class=fsSubmitButton name="roster" value="View Your Class Rosters"></li>
		<li style="float:right"><input type="submit" class=fsSubmitButton name="logout" value="logout" formaction="logout.php"><li>
	</form>
</ul>

<?php
	/* variables for determining which page to display */
	$transcript = $_POST["transcript"];
	$grades = $_POST["grades"];
	$roster = $_POST["roster"];
	$transcript_search = $_POST["transcript_search"];
	$transcriptName_search = $_POST["transcriptName_search"];
	$grade_search = $_POST["grade_search"];
	$grade_Nsearch = $_POST["grade_Nsearch"];
	$change_grade = $_POST["change_grade"];
	$change_grade2 = $_POST["change_grade2"];

	/* variables for displaying transcript */
	$student_id = $_POST["student_id"];
	$fname = $_POST["fname"];
	$lname = $_POST["lname"];

	/* variables for changing students grades */
	$new_grade = $_POST["new_grade"];
	$sid = $_POST["sid"];
	$dept = $_POST["dept"];
	$cid = $_POST["cid"];
	$semester = $_POST["semester"];
	$year = $_POST["year"];

	/* login credentials */
	$servername = "localhost";
	$username = "team3";
	$password = "e9Yez5FL";
	$dbname = "team3";

	/* connect to database */
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	/* display page to search for a student's transcript */
	if($transcript){
		echo '<form method="post" action="prof.php">';
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
		$query = "SELECT p.firstname, p.lastname
			FROM personalinfo p
			WHERE p.firstname = '$fname' AND p.lastname = '$lname';";

		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);

		$user_exists = 0;
		if (mysqli_num_rows($result) > 0){
			echo "<h2>".$row["firstname"]." ".$row["lastname"]."</h2>";
			$user_exists = 1;
		}

		$has_student = 0;
		$query = "SELECT u.ID
			  FROM transcripts t, personalinfo p, users u
			  WHERE p.firstname = '$fname' AND p.lastname = '$lname' AND p.id = t.studentid
			  		AND u.email = '$_SESSION[email]' AND u.id = t.professorid;";

		$result = mysqli_query($conn, $query);
		if(mysqli_num_rows($result) > 0){
			$has_student = 1;
		}

		/* get transcript information */
		$query = "SELECT t.dept, t.coursenum, c.credithours, t.grade, t.year, t.semester
			FROM transcripts t, courses c, personalinfo p
			WHERE t.coursenum = c.coursenum AND t.dept = c.dept AND
			p.firstname = '$fname' AND p.lastname = '$lname' AND t.studentid = p.id
			ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($conn, $query);

		$total_credits = 0;
		$weight = 0;
		$sum = 0;

		/* display transcript information */
		if (mysqli_num_rows($result) > 0 && $has_student == 1){
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
				echo "<td>".$row["coursenum"]."</td>";
				echo "<td>".$row["credithours"]."</td>";
				echo "<td>".$row["grade"]."</td>";
				echo "<td>".$row["semester"]."</td>";
				echo "<td>".$row["year"]."</td>";

				echo "</tr>";

				/* gpa calculation */
				$weight = $row["credithours"];
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
		else if ($user_exists == 1 && $has_student == 0){
			echo "This student has not taken any of your classes yet";
		}
		else {
			echo "Sorry there aren't any students with that name";
		}
	}

	/* display transcript data */
	if($transcript_search){
		/* get and display student name*/
		$query = "SELECT p.firstname, p.lastname
			FROM personalinfo p
			WHERE p.id = '$student_id';";

		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);

		$user_exists = 0;
		if (mysqli_num_rows($result) > 0){
			echo "<h2>".$row["firstname"]." ".$row["lastname"]."</h2>";
			$user_exists = 1;
		}

		$has_student = 0;
		$query = "SELECT u.ID
			  FROM transcripts t, users u
			  WHERE t.studentid= '$student_id'
			  		AND u.email = '$_SESSION[email]' AND u.id = t.professorid;";

		$result = mysqli_query($conn, $query);
		if(mysqli_num_rows($result) > 0){
			$has_student = 1;
		}

		/* get transcript information */
		$query = "SELECT t.dept, t.coursenum, c.credithours, t.grade, t.year, t.semester
			FROM transcripts t, courses c
			WHERE t.coursenum = c.coursenum AND t.dept = c.dept AND
			t.studentid = '$student_id'
			ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($conn, $query);

		$total_credits = 0;
		$weight = 0;
		$sum = 0;

		/* display transcript information */
		if (mysqli_num_rows($result) > 0 && $has_student == 1){
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
				echo "<td>".$row["coursenum"]."</td>";
				echo "<td>".$row["credithours"]."</td>";
				echo "<td>".$row["grade"]."</td>";
				echo "<td>".$row["semester"]."</td>";
				echo "<td>".$row["year"]."</td>";

				echo "</tr>";

				/* gpa calculation */
				$weight = $row["credithours"];
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
		else if ($user_exists == 1 && $has_student == 0){
			echo "This student has not taken any of your classes yet";
		}
		else {
			echo "Sorry that is not a valid student ID";
		}
	}

	/* display page to search for a student to change grades */
	if($grades){
		echo '<form method="post" action="prof.php">';
		echo '<h4>Enter a Student Name:</h4>';
		echo 'First Name: <input type="text" name="fname"><br>';
		echo 'Last Name: <input type="text" name="lname"><br>';
		echo '<input type="submit" name="grade_Nsearch" value="Search">';
		echo '<h4>Enter a Student ID:</h4> <input type="text" name="student_id"><br>';
		echo '<input type="submit" name="grade_search" value="Search">';
		echo '</form>';
	}

	/* change students grade and display confirmation */
	if($change_grade || $change_grade2){
		$query = "UPDATE transcripts
			SET grade = '$new_grade'
			WHERE studentid = '$sid' AND dept = '$dept' AND coursenum = '$cid' AND
			semester = '$semester' AND year = '$year';";

		if(strcmp($new_grade, "A") == 0 || strcmp($new_grade, "A-") == 0 || strcmp($new_grade, "B+") == 0 || strcmp($new_grade, "B") == 0 || strcmp($new_grade, "B-") == 0 || strcmp($new_grade, "C+") == 0 || strcmp($new_grade, "C") == 0 || strcmp($new_grade, "F") == 0) {
			$result = mysqli_query($conn, $query);
		} else {
			echo "Invalid grade <br/>";
		}

		if($change_grade){
			$_SESSION["reload"] = 1;
			$_SESSION["sid"] = $sid;
		}
		else if($change_grade2){
			$_SESSION["reload2"] = 1;
		}

		else {
			echo "Sorry this isn't a valid student ID";
		}
	}

	/* display page change a students grades */
	if($grade_Nsearch){
	   	/* get and display student name*/
		$query = "SELECT p.firstname, p.lastname
			FROM personalinfo p
			WHERE p.firstname = '$fname' AND p.lastname = '$lname';";

		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) == 0){
			echo "This student doesn't exist";
		}
		else{
			$row = mysqli_fetch_assoc($result);

			echo "<h2>".$row["firstname"]." ".$row["lastname"]."</h2>";

			/* get student grade information */
			$query = "SELECT t.studentid, t.dept, t.coursenum, t.grade, t.semester, t.year
				FROM transcripts t, personalinfo p, users u
				WHERE p.firstname = '$fname'  AND p.lastname = '$lname' AND p.id = t.studentid
                        	AND u.email = '$_SESSION[email]' AND u.id = t.professorid
				ORDER BY t.year, t.semester DESC;";

    		$result = mysqli_query($conn, $query);

			/* display student's current grade information with option to change */
			if (mysqli_num_rows($result) > 0){
				echo "<table>";
				echo "<tr><th colspan=2>Course</th><th>Semester</th><th>Year</th><th>Grade</th><th></th></tr>";
				while ($row = mysqli_fetch_assoc($result)){
					echo "<tr>";

					echo "<td>".$row["dept"]."</td><td>".$row["coursenum"]."</td><td>".$row["semester"]."</td><td>".$row["year"]."</td>";
					echo "<td><form method='post' action='prof.php'>";
					echo "<input type='text' name='new_grade' value=".$row["grade"].">";
					echo "</td>";
					echo "<td><input type='submit' name='change_grade' value='Change'></td>";

					/* pass through info needed to change grade */
					echo "<input type='hidden' name='sid' value=".$row["studentid"].">";
					echo "<input type='hidden' name='dept' value=".$row["dept"].">";
					echo "<input type='hidden' name='cid' value=".$row["coursenum"].">";
					echo "<input type='hidden' name='semester' value=".$row["semester"].">";
					echo "<input type='hidden' name='year' value=".$row["year"]."></form>";

					echo "</tr>";
				}
				echo "</table>";

		    	}
			else {
				echo "You do not have this student";
			}
		}
	}

	/* display page change a students grades */
	if($grade_search || $_SESSION["reload"] == 1){
		/* get and display student name*/

		if ($_SESSION["reload"] == 1) {
			$student_id = $_SESSION["sid"];
		}

		$query = "SELECT p.firstname, p.lastname
			FROM personalinfo p
			WHERE p.id = '$student_id';";

		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);

		$notinclass = 0;
		if (mysqli_num_rows($result) > 0){
			echo "<h2>".$row["firstname"]." ".$row["lastname"]."</h2>";
			$notinclass = 1;
		}

		/* get student grade information */
		$query = "SELECT t.studentid, t.dept, t.coursenum, t.grade, t.semester, t.year
			FROM transcripts t, users u
			WHERE t.studentid = '$student_id' AND u.email = '$_SESSION[email]' AND u.id = t.professorid
			ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($conn, $query);

		/* display student's current grade information with option to change */
		if (mysqli_num_rows($result) > 0){
			echo "<table>";
			echo "<tr><th colspan=2>Course</th><th>Semester</th><th>Year</th><th>Grade</th><th></th></tr>";
			while ($row = mysqli_fetch_assoc($result)){
				echo "<tr>";

				echo "<td>".$row["dept"]."</td><td>".$row["coursenum"]."</td><td>".$row["semester"]."</td><td>".$row["year"]."</td>";
				echo "<td><form method='post' action='prof.php'>";
				echo "<input type='text' name='new_grade' value='".$row["grade"]."'>";
				echo "</td>";
				echo "<td><input type='submit' name='change_grade' value='Change'></td>";

				/* pass through info needed to change grade */
				echo "<input type='hidden' name='sid' value=".$row["studentid"].">";
				echo "<input type='hidden' name='dept' value=".$row["dept"].">";
				echo "<input type='hidden' name='cid' value=".$row["coursenum"].">";
				echo "<input type='hidden' name='semester' value=".$row["semester"].">";
				echo "<input type='hidden' name='year' value=".$row["year"]."></form>";

				echo "</tr>";
			}
			echo "</table>";

			$_SESSION["reload"] = 0;
			$_SESSION["reload2"] = 0;
			$_SESSION["sid"] = "nobody";
		}
		else if ($notinclass == 1){
			echo "You do not have this student";
		}
		else {
			echo "Sorry, this isn't a valid ID";
		}
	}


	/* show professors class rosters */
	if($roster || $_SESSION["reload2"] == 1){
		$query = "SELECT p.id, p.firstname, p.lastname, t.dept, t.coursenum, c.semester, c.year, t.grade
			FROM personalinfo p, transcripts t, courses c,
			WHERE p.id = t.studentid AND u.id = t.profID AND u.email = '$_SESSION[email]'
					AND c.semester = t.semester AND c.year = t.year AND t.coursenum = c.coursenum AND t.dept = c.dept;";

		$result = mysqli_query($conn, $query);

		/* display class rosters */
		if (mysqli_num_rows($result) > 0){
			echo "<h2>Classes</h2>";
			echo "<table>";
			$cur_dept = ""; //track current class
			$cur_id = ""; //track current class
			while ($row = mysqli_fetch_assoc($result)){
				//start new table when class changes
				if($cur_dept != $row["dept"] || $cur_id != $row["coursenum"]){
					echo "</table><br><table>";
					echo "<tr><th colspan=2>Course</th><th>Student ID</th><th colspan=2>Name</th><th colspan=2>Update Grade</th></tr>";
					$cur_dept = $row["dept"];
					$cur_id = $row["coursenum"];
				}

				//print roster
				echo "<tr>";

				echo "<td>".$row["dept"]."</td>";
				echo "<td>".$row["coursenum"]."</td>";
				echo "<td>".$row["id"]."</td>";
				echo "<td>".$row["firstname"]."</td>";
				echo "<td>".$row["lastname"]."</td>";

				echo "<td>";
				echo "<form method='post' action='prof.php'>";
				echo "<input type='text' name='new_grade' value=".$row["grade"].">";
				echo "</td>";
				echo "<td><input type='submit' name='change_grade2' value='Change'></td>";
				echo "</tr>";

				/* pass through info needed to change grade */
				echo "<input type='hidden' name='sid' value=".$row["id"].">";
				echo "<input type='hidden' name='dept' value=".$row["dept"].">";
				echo "<input type='hidden' name='cid' value=".$row["coursenum"].">";
				echo "<input type='hidden' name='semester' value=".$row["semester"].">";
				echo "<input type='hidden' name='year' value=".$row["year"]."></form>";
			}
			echo "</table>";
			$_SESSION["reload2"] = 0;
			$_SESSION["reload"] = 0;
		}
		else {
			echo "You don't have anyone registered for your classes currently";
		}
	}

	mysqli_close($conn);
?>

</body>
</html>
