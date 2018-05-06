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
    include 'db-connect.php';

    /* extract user */
	$user = $_SESSION["email"];

    /* used for page display */
    $list = $_POST["list"];
    $grid = $_POST["grid"];

    $year = $_POST["year"];
    $semester = $_POST["semester"];

	/* display schedule */
	if($list) {
        echo "<h2>Schedule</h2>";

		$year = $_POST["year"];
		$semester = $_POST["semester"];

		/* get schedule */
		$query = "SELECT t.dept, t.coursenum, c.section, c.day, c.time, c.title
			FROM users u, transcripts t, courses c
			WHERE u.id=t.studentid AND u.email = '$user' AND t.coursenum = c.coursenum AND t.dept = c.dept AND t.semester = '$semester' AND t.year = '$year'
			ORDER BY day;";

		$result = mysqli_query($connection, $query);

		/* display schedule */
		if (mysqli_num_rows($result) > 0) {
			echo "<br/>";
			echo "<table>";
			echo "<tr><th colspan=2>Course</th><th>Section</th><th>Title</th><th>Day</th><th>Time</th></tr>";
			while($row = mysqli_fetch_assoc($result)) {
				echo "<tr>";
				echo "<td>".$row["dept"]."</td><td>".$row["coursenum"]."</td><td>".$row["section"]."</td><td>".$row["title"]."</td><td>".$row["day"]."</td><td>".$row["time"]."</td>";
				echo "</tr>";
			}
			echo "</table>";
		} else {
			echo "No results, please check semester and year are correct <br/>";
		}
	}
    else if($grid){
        echo "<h2>Schedule</h2>";

		$year = $_POST["year"];
		$semester = $_POST["semester"];

		/* get schedule */
		$query = "SELECT t.dept, t.coursenum, c.section, c.day, c.time, c.title
			FROM users u, transcripts t, courses c
			WHERE u.id=t.studentid AND u.email = '$user' AND t.coursenum = c.coursenum AND t.dept = c.dept AND t.semester = '$semester' AND t.year = '$year'
			ORDER BY day;";

		$result = mysqli_query($connection, $query);

		/* collect schedule info */
        $no_results = 0;
        $week = array("M", "T", "W", "R", "F");
        $times = array();
        $days = array();
        $depts = array();
        $coursenums = array();
        $titles = array();
        $sections = array();
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)) {
                array_push($time, $row["time"]);
                array_push($day, $row["day"]);
                array_push($dept, $row["dept"]);
                array_push($coursenum, $row["coursenum"]);
                array_push($title, $row["title"]);
                array_push($section, $row["section"]);
			}
		}
        else {
			echo "No results, please check semester and year are correct <br/>";
            $no_results = 1;
		}

        /* display schedule */
        if($no_results != 1){
            echo "<table>";
            echo "<tr><th>Time</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th></tr>";
            for($t = 12; d <= 22; d++){
                echo "<tr>";
                echo "<td>" . $t . "00:00" . "</td>";
                $cur_time = $t . "00:00";
                for ($d = 0; $d < 5; $d++) {
                    $found_class = 0;
                    $same_day = array();
                    for($i = 0; $i < count($coursenums); $i++){
                        if($days[$i] == $week[$d]){
                            array_push($same_day, $coursenums[$i]);
                        }
                    }

                    for($i = 0; $i < count($same_day); $i++){
                        $key = array_search($same_day[$i], $coursenums);
                        if($times[$key] == $cur_time){
                            echo '<tdbgcolor="#FF0000">';
                            echo $titles[$key];
                            $found_class = 1;
                        }
                    }
                    if($found_class == 0){
                        echo "<td>";
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }

            echo "</table>";
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
		echo    "<input type='submit' value='View Schedule as List' name='list' />";
        echo    "<input type='submit' value='View Schedule on Calendar' name='grid' />";
		echo "</form>";
	}

?>

</body>
</html>
