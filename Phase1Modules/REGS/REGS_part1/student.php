<?php
	session_start();
	if (strcmp($_SESSION["role"], "student") != 0) {
		die("ACCESS DENIED");
	}
?>

<html>
<head>
	<title> Student </title>

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

<!-- Handling button presses -->
<?php
	/* login credentials */
	$servername = "localhost";
	$username= "team3";
	$password= "e9Yez5FL";
	$dbname = "team3";

	/* variables for managing display */
	$schedule = $_POST["schedule"];
	$transcript = $_POST["transcript"];
	$update = $_POST["update"];
	$change = $_POST["change"];
	$schedule_display = $_POST["schedule_display"];

	/* extract username */
	$user = $_SESSION["username"];

	/* connect to database */
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if(!conn) {
		die("Connection failed: " . mysqli_connect_error());
	}

	/* handle schedule display */
	if($schedule) {
		/* prompt for user entry of current year and semester */
		echo "<p> Enter current year and semester (spring or fall) </p>";
		echo "<form method='post' action='student.php'>";
		echo    "<label for='year'>Year: </label>";
		echo    "<input type='text' id='year' name='year' /> <br/>";
		echo    "Semester: ";
		echo    "<input type='radio' name='semester' value='fall'>Fall";
		echo    "<input type='radio' name='semester' value='spring'>Spring <br/>";
		echo    "<input type='submit' value='Enter' name='schedule_display' />";
		echo "</form>";
	}

	/*display schedule */
	if($schedule_display) {
		$year = $_POST["year"];
		$semester = $_POST["semester"];

		/* get schedule */
		$query = "SELECT t.dept, t.cid, t.sectionNum, c.day, c.classTime
			FROM students s, transcripts t, courses c
			WHERE s.id=t.sid AND s.username = '".$user."' AND t.cid = c.cid AND t.dept = c.dept AND t.semester = '".$semester."' AND t.year = '".$year."'
			ORDER BY day;";

		$result = mysqli_query($conn, $query);

		/* display schedule */
		if (mysqli_num_rows($result) > 0) {
			echo "<br/>";
			echo "<table>";
			echo "<tr><th colspan=2>Course</th><th>Section</th><th>Day</th><th>Time</th></tr>";
			while($row = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>".$row["dept"]."</td><td>".$row["cid"]."</td><td>".$row["sectionNum"]."</td><td>".$row["day"]."</td><td>".$row["classTime"]."</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "No results, please check semester and year are correct <br/>";
		}
	}

	/* handle transcript display */
	if($transcript) {
		/* get and display student name */
		$query = "SELECT fname, lname FROM students
			WHERE username = '".$user."';";
		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);

		echo "<h2>".$row["fname"]." ".$row["lname"],"</h2>";

		/* get transcript */

		$query = "SELECT t.dept, t.cid, c.cHours, t.grade, t.year, t.semester
			      FROM transcripts t, courses c, students s
			      WHERE t.cid = c.cid AND t.dept = c.dept AND
				  		s.id = t.sid AND s.username = '$user'
			 	  ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($conn, $query);

		$total_credits = 0;
		$weight = 0;
		$sum = 0;

		/* display transcript */
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
		else{
			echo "You not taken any classes yet";
		}
	}

	/* handle update personal info display */
	if($update) {
		echo "<h2 style='text-align:center'> Update Personal Info </h2>";
		echo "<p> This is the information you have currently entered</p>";
		$query = "SELECT s.id, s.username, s.fname, s.lname, s.street, s.city, s.email
			      FROM  students s
			      WHERE s.username = '$user';";
		$result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result)>0) {
			echo '<table style="width:25%"';
			echo '<tr>';
			echo '<th>Student ID</th>';
			echo '<th>First Name</th>';
			echo '<th>Last Name</th>';
			echo '<th>Street Address</th>';
			echo '<th>City</th>';
			echo '<th>Email</th>';
			echo '</tr>';
			while($row = mysqli_fetch_assoc($result)) {
				echo '<tr>';
				echo'<td>' . $row["id"] . '</td>';
				echo'<td>' . $row["fname"] . '</td>';
				echo'<td>' . $row["lname"] . '</td>';
				echo'<td>' . $row["street"] . '</td>';
				echo'<td>' . $row["city"] . '</td>';
				echo'<td>' . $row["email"] . '</td>';
				echo '</tr>';
			}
			echo '</table><br />';
		}
		echo "<p> Fill in what you would like to change </p>";
		echo "<p> Leave all else blank, then hit Change </p>";

		echo "<form method='post' action='student.php'>";
		echo    "<label for='pass'>Password: </label>";
		echo    "<input type='text' id='pass' name='pass' /> <br/>";
		echo    "<label for='first'>First name: </label>";
		echo    "<input type='text' id='first' name='first' /> <br/>";
		echo    "<label for='last'>Last name: </label>";
		echo    "<input type='text' id='last' name='last' /> <br/>";
		echo    "<label for='street'>Street Address: </label>";
		echo    "<input type='text' id='street' name='street' /> <br/>";
		echo    "<label for='city'>City: </label>";
		echo    "<input type='text' id='city' name='city' /> <br/>";
		echo    "<label for='email'>Email: </label>";
		echo    "<input type='text' id='email' name='email' /> <br/>";
		echo    "<input type='submit' value='Change' name='change' />";
		echo "</form>";

	}

	/* handle changing of personal info */
	if($change) {
		$pass = $_POST["pass"];
		$first = $_POST["first"];
		$last = $_POST["last"];
		$street = $_POST["street"];
		$city = $_POST["city"];
		$email = $_POST["email"];

		/* for finding correct row */
		$user = $_SESSION["username"];

		/* queries for changing info */
		if($pass) {
			$query = "UPDATE users SET password='".$pass."' WHERE username='".$user."';";

			$result = mysqli_query($conn, $query);

			if($result) {
				echo "Successfully updated password";
				echo "</br>";

			} else {
				echo "Failed to update password";
				echo "</br>";
			}
		}
		if($first) {
			$query = "UPDATE students SET fname='".$first."' WHERE username='".$user."';";

			$result = mysqli_query($conn, $query);

			if($result) {
				echo "Successfully updated fist name";
				echo "</br>";
			} else {
				echo "Failed to update fist name";
				echo "</br>";
			}
		}
		if($last) {
			$query = "UPDATE students SET lname='".$last."' WHERE username='".$user."';";

			$result = mysqli_query($conn, $query);

			if($result) {
				echo "Successfully updated last name";
				echo "</br>";
			} else {
				echo "Failed to update last name";
				echo "</br>";
			}
		}
		if($street) {
			$query = "UPDATE students SET street='".$street."' WHERE username='".$user."';";

			$result = mysqli_query($conn, $query);

			if($result) {
				echo "Successfully updated street";
				echo "</br>";
			} else {
				echo "Failed to update street";
				echo "</br>";
			}
		}
		if($city) {
			$query = "UPDATE students SET city='".$city."' WHERE username='".$user."';";

			$result = mysqli_query($conn, $query);

			if($result) {
				echo "Successfully updated city";
				echo "</br>";
			} else {
				echo "Failed to update city";
				echo "</br>";
			}
		}
		if($email) {
			$query = "UPDATE students SET email='".$email."' WHERE username='".$user."';";

			$result = mysqli_query($conn, $query);

			if($result) {
				echo "Successfully updated email";
				echo "</br>";
			} else {
				echo "Failed to update email";
				echo "</br>";
			}
		}
	}
?>

</body>
</html>
