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
		<input type="submit" value="Search CID" name="idSearch" />
		<input type="submit" value="Search Department" name="deptSearch" />
		<input type="submit" value="Show All Classes" name="all" /> <br/>
		<h2 style="text-align:center"> Register For Classes </h2>
		<p> Enter CRN (obtained from search) </p>
		<label for="regCRN">Class CRN: </label>
		<input type="text" id="regCRN" name="regCRN" /> <br/>
		<input type="submit" value="Register" name="reg" />
		<input type="submit" value="Drop" name="drop" />
	</form>

<?php
	/* credentials for database */
	$servername = "localhost";
	$username = "team3";
	$password = "e9Yez5FL";
	$dbname = "team3";

	/* variables for button handling */
	$idSearch = $_POST["idSearch"];
	$deptSearch = $_POST["deptSearch"];
	$all = $_POST["all"];
	$reg = $_POST["reg"];
	$drop = $_POST["drop"];

	$user = $_SESSION["username"];

	/* connect tod database */
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	/* manage displays */

	/* show all classes */
	if($all) {
		$query = "SELECT c.crn, c.dept, c.cid, c.sectionNum, p.name, c.year, c.semester, c.cHours, c.day, c.classTime
			FROM courses c, professors p
			WHERE c.profID = p.id
			ORDER BY c.crn;";
		$result = mysqli_query($conn, $query);

		echo "<h2> All Classes </h2>";

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

	/* display searched for class id */
	if($idSearch) {
		$id = $_POST["search"];

		$query = "SELECT c.crn, c.dept, c.cid, c.sectionNum, p.name, c.year, c.semester, c.cHours, c.day, c.classTime
			FROM courses c, professors p
			WHERE c.profID = p.id AND c.cid = '".$id."'
			ORDER BY c.crn;";
		$result = mysqli_query($conn, $query);

		echo "<h2> ID Search Results </h2>";

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

		} else {
			echo "<br/><br/>";
			echo "No results, please make sure search is correct";
		}
	}

	/* display searched for dept classes */
	if($deptSearch) {
		$dept = $_POST["search"];

		$query = "SELECT c.crn, c.dept, c.cid, c.sectionNum, p.name, c.year, c.semester, c.cHours, c.day, c.classTime
			FROM courses c, professors p
			WHERE c.profID = p.id AND c.dept = '".$dept."'
			ORDER BY c.crn;";
		$result = mysqli_query($conn, $query);

		echo "<h2> Department Search Results </h2>";

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

		} else {
			echo "<br/><br/>";
			echo "No results, please make sure search is correct";
		}
	}

	/* handle registering for class */
	if($reg) {
		$class = $_POST["regCRN"];

		/* get desired class info */
		$query = "SELECT * FROM courses WHERE crn = '".$class."';";
		$result = mysqli_query($conn, $query);

		$classInfo = mysqli_fetch_assoc($result); /* stores all class info */

		/* get student id */
		$query = "SELECT * FROM students WHERE username = '".$user."';";
		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);
		$sid = $row["ID"];

		/* check for prereqs */
		$passPrereqs = 1;
		/* search for class in prereq1
		 * if has one, check if is in transcript
		 * then check for class in prereq2
		 * if is, check if is in transcript for student
		 * if either of those checks fail, change passPrereqs to 0 */
		$query = "SELECT * FROM prereq1 WHERE crn = '".$classInfo["CRN"]."';";
		$prereq = mysqli_query($conn, $query);
		$prereqCRN = mysqli_fetch_assoc($prereq);
		if(mysqli_num_rows($prereq) == 1) {
			$query = "SELECT * FROM transcripts t, courses c WHERE t.sid = '".$sid."' AND c.cid = t.cid AND c.dept = t.dept AND c.crn = '".$prereqCRN["preCRN"]."';";
			$taken = mysqli_query($conn, $query);
			if(mysqli_num_rows($taken) == 0) {
				$passPrereqs = 0;
			}
		}

		/* second prereq */
		$query = "SELECT * FROM prereq2 WHERE crn = '".$classInfo["CRN"]."';";
		$prereq = mysqli_query($conn, $query);
		$prereqCRN = mysqli_fetch_assoc($prereq);
		if(mysqli_num_rows($prereq) == 1) {
			$query = "SELECT * FROM transcripts t, courses c WHERE t.sid = '".$sid."' AND c.cid = t.cid AND c.dept = t.dept AND c.crn = '".$prereqCRN["preCRN"]."';";
			$taken = mysqli_query($conn, $query);
			if(mysqli_num_rows($taken) == 0) {
				$passPrereqs = 0;
			}
		}

		/*error message */
		if ($passPrereqs == 0) {
			echo "You do not have the required prereqs </br>";
		}

		/* perform checks for schedule conflicts */
		/* get student schedule (just day and time) */
		$query = "SELECT c.crn, c.day, c.classTime FROM courses c, transcripts t WHERE c.cid = t.cid AND c.dept = t.dept AND c.year = t.year AND c.semester = t.semester AND t.sid = '".$sid."';";
		$schedule = mysqli_query($conn, $query);

		/* check times
		 * only need to check if day is same and either time is 4 */
		$passSched = 1;
		while($row = mysqli_fetch_assoc($schedule)) {
			/* check if already registered for class */
			if ($classInfo["CRN"] == $row["crn"]) {
				$passSched = 0;
				echo "Already registered for class <br/>";
			}


			if ($classInfo["day"] == $row["day"]) {
				if ($classInfo["classTime"] == $row["classTime"]) {
					$passSched = 0;
				}

				if ($classInfo["classTime"] == "16:00:00" || $row["classTime"] == "16:00:00") {
					$passSched = 0;
				}
			}
		}

		/* failure message */
		if($passSched == 0) {
			echo "You have a schedule conflict <br/>";
		}

		/* insert into transcript if allowed*/
		if($passSched == 1 && $passPrereqs == 1) {
			$query = "INSERT INTO transcripts VALUES
				('".$sid."', '".$classInfo["CID"]."', '".$classInfo["dept"]."', '".$classInfo["profID"]."', '".$classInfo["year"]."', '".$classInfo["semester"]."', '".$classInfo["sectionNum"]."', 'IP');";

			$result = mysqli_query($conn, $query);

			if(!$result) {
				echo "Failed to register for class";
			}else {
				echo "Successfully registered for class";
			}
		}
	}

	if($drop) {
		$class = $_POST["regCRN"];

		/* get desired class info */
		$query = "SELECT * FROM courses WHERE crn = '".$class."';";
		$result = mysqli_query($conn, $query);

		$classInfo = mysqli_fetch_assoc($result); /* stores all class info */

		/* get student id */
		$query = "SELECT * FROM students WHERE username = '".$user."';";
		$result = mysqli_query($conn, $query);

		$row = mysqli_fetch_assoc($result);
		$sid = $row["ID"];

		/* remove class from transcript */
		$query = "DELETE FROM transcripts  WHERE sid = '".$sid."' AND cid = '".$classInfo["CID"]."' AND dept = '".$classInfo["dept"]."'
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
