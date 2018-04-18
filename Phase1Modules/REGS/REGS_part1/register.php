<?php
	session_start();
	if (strcmp($_SESSION["role"], "student") != 0) {
		die("ACCESS DENIED");
	}
?>

<html>
<head>
	<title> Register </title>

<!-- Format menu bar -->
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<img src="gw_logo.png" alt="gw logo">
<!-- Create menu bar -->
<ul>
	<form method="post" action="student.php">
		<li><input type="submit" class=fsSubmitButton value="View Schedule" name="schedule"></li>
		<li><input type="submit" class=fsSubmitButton value="View Transcript" name="transcript"></li>
		<li><input type="submit" class=fsSubmitButton formaction="register.php" value="Register" name="register"></li>
		<li><input type="submit" class=fsSubmitButton value="Update Personal Info" name="update"></li>
		<li style="float:right"><input type="submit" class=fsSubmitButton formaction="logout.php" value="Logout" name="logout"></li>
</form>
</ul>

	<h2 style="text-align:center"> Search For Classes </h2>
	<p> Search by department or course number </p>
	<form method="post" action="register.php">
		<label for="search">SEARCH: </label>
		<input type="text" id="search" name="search" /> <br/>
		<input type="submit" value="Search Course Number" name="idSearch" />
		<input type="submit" value="Search Department" name="deptSearch" />
		<input type="submit" value="Show All Classes" name="all" /> <br/>
		<h2 style="text-align:center"> Register For Classes </h2>
		<p> Enter Course ID (obtained from search) </p>
		<label for="regCRN">Course ID: </label>
		<input type="text" id="regCRN" name="regCRN" /> <br/>
		<input type="submit" value="Register" name="reg" />
		<input type="submit" value="Drop" name="drop" />
	</form>

<?php
	/* credentials for database */
	$servername = "127.0.0.1";
	$username = "teamA2";
	$password = "Ar9x5Y";
	$dbname = "teamA2";

	/* variables for button handling */
	$idSearch = $_POST["idSearch"];
	$deptSearch = $_POST["deptSearch"];
	$all = $_POST["all"];
	$reg = $_POST["reg"];
	$drop = $_POST["drop"];

	$user = $_SESSION["email"];

	/* connect tod database */
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	/* manage displays */

	/* show all classes */
	if($all) {
		$query = "SELECT c.courseid, c.dept, c.coursenum, c.section, p.firstname, p.lastname, c.year, c.semester, c.credithours, c.day, c.time
			FROM courses c, personalinfo p
			WHERE c.professorid = p.id
			ORDER BY c.courseid;";
		$result = mysqli_query($conn, $query);

		echo "<h2> All Classes </h2>";

		if (mysqli_num_rows($result) > 0) {
			echo "<table>";
			echo "<tr><th>course id</th><th colspan=2>Course</th><th>Section</th><th colspan=2>Professor</th><th>Year</th><th>Semester</th><th>Credits</th><th>Day</th><th>Time</th><th>Prereq1 CRN</th><th>Prereq2 CRN</th>";

			while($row = mysqli_fetch_assoc($result)) {

				echo "<tr>";
				echo  "<td>".$row["courseid"]."</td><td>".$row["dept"]."</td><td>".$row["coursenum"]."</td><td>".$row["section"]."</td><td>".$row["firstname"]."</td><td>".$row["lastname"]."</td><td>".$row["year"]."</td><td>".$row["semester"]."</td><td>".$row["credithours"]."</td><td>".$row["day"]."</td><td>".$row["time"]."</td>";

				/* get any prereqs */
				$query = "SELECT * FROM prereqs
					WHERE courseid = '".$row["courseid"]."';";
				$prereq = mysqli_query($conn, $query);

				$prereqCRN = mysqli_fetch_assoc($prereq);
				echo "<td>".$prereqCRN["prereqid"]."</td>";

				$prereqCRN = mysqli_fetch_assoc($prereq);
				echo "<td>".$prereqCRN["prereqid"]."</td>";

				echo "</tr>";
			}
			echo "</table>";

		}
	}

	/* display searched for class id */
	if($idSearch) {
		$id = $_POST["search"];

		$query = "SELECT c.courseid, c.dept, c.coursenum, c.section, p.firstname, p.lastname, c.year, c.semester, c.credithours, c.day, c.time
			FROM courses c, personalinfo p
			WHERE c.professorid = p.id AND c.coursenum = '$id'
			ORDER BY c.courseid;";
		$result = mysqli_query($conn, $query);

		echo "<h2> ID Search Results </h2>";

		if (mysqli_num_rows($result) > 0) {
			echo "<table>";
			echo "<tr><th>course id</th><th colspan=2>Course</th><th>Section</th><th colspan=2>Professor</th><th>Year</th><th>Semester</th><th>Credits</th><th>Day</th><th>Time</th><th>Prereq1 CRN</th><th>Prereq2 CRN</th>";

			while($row = mysqli_fetch_assoc($result)) {

				echo "<tr>";
				echo  "<td>".$row["courseid"]."</td><td>".$row["dept"]."</td><td>".$row["coursenum"]."</td><td>".$row["section"]."</td><td>".$row["firstname"]."</td><td>".$row["lastname"]."</td><td>".$row["year"]."</td><td>".$row["semester"]."</td><td>".$row["credithours"]."</td><td>".$row["day"]."</td><td>".$row["time"]."</td>";

				/* get any prereqs */
				$query = "SELECT * FROM prereqs
					WHERE courseid = '".$row["courseid"]."';";
				$prereq = mysqli_query($conn, $query);

				$prereqCRN = mysqli_fetch_assoc($prereq);
				echo "<td>".$prereqCRN["prereqid"]."</td>";

				$prereqCRN = mysqli_fetch_assoc($prereq);
				echo "<td>".$prereqCRN["prereqid"]."</td>";

				echo "</tr>";
			}
			echo "</table>";

		} else {
			echo "<br/><br/>";
			echo "No results, please make sure search is correct";
		}
	}

	/* display searched for dept classes */
	if($deptSearch) {
		$dept = $_POST["search"];

		$query = "SELECT c.courseid, c.dept, c.coursenum, c.section, p.firstname, p.lastname, c.year, c.semester, c.credithours, c.day, c.time
			FROM courses c, personalinfo p
			WHERE c.professorid = p.id AND c.dept = '$dept'
			ORDER BY c.courseid;";
		$result = mysqli_query($conn, $query);

		echo "<h2> Department Search Results </h2>";

		if (mysqli_num_rows($result) > 0) {
			echo "<table>";
			echo "<tr><th>course id</th><th colspan=2>Course</th><th>Section</th><th colspan=2>Professor</th><th>Year</th><th>Semester</th><th>Credits</th><th>Day</th><th>Time</th><th>Prereq1 CRN</th><th>Prereq2 CRN</th>";

			while($row = mysqli_fetch_assoc($result)) {

				echo "<tr>";
				echo  "<td>".$row["courseid"]."</td><td>".$row["dept"]."</td><td>".$row["coursenum"]."</td><td>".$row["section"]."</td><td>".$row["firstname"]."</td><td>".$row["lastname"]."</td><td>".$row["year"]."</td><td>".$row["semester"]."</td><td>".$row["credithours"]."</td><td>".$row["day"]."</td><td>".$row["time"]."</td>";

				/* get any prereqs */
				$query = "SELECT * FROM prereqs
					WHERE courseid = '".$row["courseid"]."';";
				$prereq = mysqli_query($conn, $query);

				$prereqCRN = mysqli_fetch_assoc($prereq);
				echo "<td>".$prereqCRN["prereqid"]."</td>";

				$prereqCRN = mysqli_fetch_assoc($prereq);
				echo "<td>".$prereqCRN["prereqid"]."</td>";

				echo "</tr>";
			}
			echo "</table>";

		} else {
			echo "<br/><br/>";
			echo "No results, please make sure search is correct";
		}
	}

	/* handle registering for class */
	if($reg) {
		$class = $_POST["regCRN"];

		/* get desired class info */
		$query = "SELECT * FROM courses WHERE courseid = '".$class."';";
		$result = mysqli_query($conn, $query);

		$classInfo = mysqli_fetch_assoc($result); /* stores all class info */

		/* get student id */
		$query = "SELECT *
					FROM personalinfo p, users u
					WHERE u.id = p.id AND u.email = '".$user."';";
		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);
		$sid = $row["id"];

		/* check for prereqs */
		$passPrereqs = 1;
		/* search for class in prereq1
		 * if has one, check if is in transcript
		 * then check for class in prereq2
		 * if is, check if is in transcript for student
		 * if either of those checks fail, change passPrereqs to 0 */
		$query = "SELECT * FROM prereqs WHERE courseid = '".$classInfo["courseid"]."';";
		$prereqs = mysqli_query($conn, $query);
		if(mysqli_num_rows($prereqs) > 0) {
			while($prereqCRN = mysqli_fetch_assoc($prereqs)){
				$query = "SELECT * FROM transcripts t, courses c WHERE t.studentid = '$sid' AND c.coursenum = t.coursenum AND c.dept = t.dept AND c.courseid = '".$prereqCRN["prereqid"]."';";
				$taken = mysqli_query($conn, $query);
				if(mysqli_num_rows($taken) == 0) {
					$passPrereqs = 0;
				}
			}
		}

		/*error message */
		if ($passPrereqs == 0) {
			echo "You do not have the required prereqs </br>";
		}

		/* perform checks for schedule conflicts */
		/* get student schedule (just day and time) */
		$query = "SELECT c.courseid, c.day, c.time
					FROM courses c, transcripts t
					WHERE c.coursenum = t.coursenum AND c.dept = t.dept AND c.year = t.year AND c.semester = t.semester AND t.studentid = '".$sid."';";
		$schedule = mysqli_query($conn, $query);

		/* check times
		 * only need to check if day is same and either time is 4 */
		$passSched = 1;
		while($row = mysqli_fetch_assoc($schedule)) {
			/* check if already registered for class */
			if ($classInfo["courseid"] == $row["courseid"]) {
				$passSched = 0;
				echo "Already registered for class <br/>";
			}


			if ($classInfo["day"] == $row["day"]) {
				if ($classInfo["time"] == $row["time"]) {
					$passSched = 0;
				}

				if ($classInfo["time"] == "16:00:00" || $row["time"] == "16:00:00") {
					$passSched = 0;
				}
			}
		}

		/* failure message */
		if($passSched == 0) {
			echo "You have a schedule conflict <br/>";
		}
		echo $classInfo["professorid "];
		/* insert into transcript if allowed*/
		if($passSched == 1 && $passPrereqs == 1 && $class != NULL) {
			$query = "INSERT INTO transcripts VALUES
				('$sid', '".$classInfo["dept"]."', '".$classInfo["coursenum"]."', '".$classInfo["professorid"]."', '".$classInfo["year"]."', '".$classInfo["semester"]."', 'IP');";

			$result = mysqli_query($conn, $query);

			if(!$result) {
				echo "Failed to register for class";
			}else {
				echo "Successfully registered for class";
			}
		}
		else if ($class == NULL){
			echo "Please enter Course ID Number";
		}
	}

	if($drop) {
		$class = $_POST["regCRN"];

		/* get desired class info */
		$query = "SELECT * FROM courses WHERE courseid = '".$class."';";
		$result = mysqli_query($conn, $query);

		$classInfo = mysqli_fetch_assoc($result); /* stores all class info */

		/* get student id */
		$query = "SELECT id
					FROM users
					WHERE email = '".$user."';";
		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);
		$sid = $row["id"];

		/* remove class from transcript */
		$query = "DELETE FROM transcripts  WHERE studentid = '$sid' AND coursenum = '".$classInfo["coursenum"]."' AND dept = '".$classInfo["dept"]."'
				AND year = '".$classInfo["year"]."' AND semester = '".$classInfo["semester"]."';";

		$result = mysqli_query($conn, $query);

		if(!$result) {
			echo "<br/> Failed to Drop Class <br/>";
		} else {
			echo "<br/> Successfully Dropped Class <br/>";
		}
	}

?>

</body>
</html>
