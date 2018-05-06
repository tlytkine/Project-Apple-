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
        echo "<h1>View Schedule</h1>";
		$year = $_POST["year"];
		$semester = $_POST["semester"];

		/* get schedule */
		$query = "SELECT t.dept, t.coursenum, c.section, t.day, t.time, c.title
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
        echo "<h1>View Schedule</h1>";
        $year = $_POST["year"];
		$semester = $_POST["semester"];

		/* get schedule */
		$query = "SELECT t.dept, t.coursenum, c.section, t.day, t.time, c.title
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
                array_push($depts, $row["dept"]);
                array_push($coursenums, $row["coursenum"]);
                array_push($sections, $row["section"]);
                array_push($days, $row["day"]);
                array_push($times, $row["time"]);
                array_push($titles, $row["title"]);
			}
		}
        else {
			echo "No results, please check semester and year are correct <br/>";
            $no_results = 1;
		}
        $keys = array(0,0,0,0,0);
        $started = array(0, 0, 0, 0, 0);
        /* display schedule */
        if($no_results != 1){
            echo "<table>";
            echo "<tr><th>Time</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th><th>Friday</th></tr>";
            /* iteratre through each time/row */
            for($t = 12; $t <= 22; $t++){
                echo "<tr>";
                echo "<td>" . $t . ":00" . "</td>";
                $cur_time = $t . ":00:00";
                /* iterate through each column/day */
                for ($d = 0; $d < 5; $d++) {
                    $found_class = 0;
                    $same_day = array();

                    /* find all courses for this day */
                    for($i = 0; $i < count($coursenums); $i++){
                        if($days[$i] == $week[$d]){
                            array_push($same_day, $coursenums[$i]);
                        }
                    }

                    /* check if any of the courses on this day start at this time */
                    for($i = 0; $i < count($same_day); $i++){
                        $keys[$d] = array_search($same_day[$i], $coursenums);
                        if($times[$keys[$d]] == $cur_time){
                            echo '<td style="background-color:black; color:white">';
                            echo $titles[$keys[$d]];
                            $found_class = 1;
                            $started[$d] = 5;
                            break;
                        }
                    }

                    //echo $started[$d];
                    if($started[$d] > 0 && $started[$d] != 5){
                        if($started[$d] == 3){
                            echo '<td style="background-color:black; color:white">';
                            echo $t-1 . ":00-";
                            echo $t+1 . ":30";
                        }
                        else{
                            echo '<td style="background-color:black">';
                        }
                        $started[$d]--;
                    }
                    else if($found_class == 0){
                        echo "<td>";
                    }
                    echo "</td>";
                }
                echo "</tr>";
                echo "<tr><td>" . $t . ":30" . "</td>";
                for ($d = 0; $d < 5; $d++) {
                    if($started[$d] == 5){ $started[$d]--; }

                    if($started[$d] > 0){
                        if($started[$d] == 4){
                            echo '<td style="background-color:black; color:white">';
                            echo $depts[$keys[$d]] ." ". $coursenums[$keys[$d]] . "-" .$sections[$keys[$d]];
                        }
                        else{
                            echo '<td style="background-color:black">';
                        }
                        $started[$d]--;
                    }
                    else{
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
        echo "<h1>Schedule Search</h1>";

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
