<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>View Schedule</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
    $allowed_user_types = array(
        "STUDENT"
    );
    include 'header.php';


    //include 'db-connect.php';
    $servername = "127.0.0.1";
    $serverusername = "teamA2";
    $serverpassword = "Ar9x5Y";
    $dbname = "teamA2";
    //$script = "../../mysql/bin/tables.sql";
    $connection = mysqli_connect($servername, $serverusername, $serverpassword, $dbname);
    if (mysqli_connect_errno()) {
    	echo "Database connection error: " . mysqli_connect_error();
    }

    /* extract user */
	$user = $_SESSION["email"];

    /* used for page display */
    $schedule_display = $_POST["schedule_display"];

    $year = $_POST["year"];
    $semester = $_POST["semester"];

	/* display schedule */
	if($schedule_display) {
        echo "<h2>Schedule</h2>";

		$year = $_POST["year"];
		$semester = $_POST["semester"];

		/* get schedule */
		$query = "SELECT t.dept, t.coursenum, c.section, c.day, c.time
			FROM users u, transcripts t, courses c
			WHERE u.id=t.studentid AND u.email = '$user' AND t.coursenum = c.coursenum AND t.dept = c.dept AND t.semester = '$semester' AND t.year = '$year'
			ORDER BY day;";

		$result = mysqli_query($connection, $query);

		/* display schedule */
		if (mysqli_num_rows($result) > 0) {
			echo "<br/>";
			echo "<table>";
			echo "<tr><th colspan=2>Course</th><th>Section</th><th>Day</th><th>Time</th></tr>";
			while($row = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>".$row["dept"]."</td><td>".$row["coursenum"]."</td><td>".$row["section"]."</td><td>".$row["day"]."</td><td>".$row["time"]."</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "No results, please check semester and year are correct <br/>";
		}
	}
    /* show search */
	else{
        echo "<h2>Schedule Search</h2>";

    	/* prompt for user entry of current year and semester */
		echo "<p> Enter year and semester (spring or fall) </p>";
		echo "<form method='post' action='view-class-schedule.php'>";
		echo    "<label for='year'>Year: </label>";
		echo    "<input type='text' id='year' name='year' /> <br/>";
		echo    "Semester: ";
		echo    "<input type='radio' name='semester' value='fall'>Fall";
		echo    "<input type='radio' name='semester' value='spring'>Spring <br/>";
		echo    "<input type='submit' value='Enter' name='schedule_display' />";
		echo "</form>";
	}

?>

</body>
</html>
