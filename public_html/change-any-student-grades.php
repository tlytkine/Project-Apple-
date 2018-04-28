<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>Change Grades</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
$allowed_user_types = array(
    "INSTRUCTOR"
);
include 'header.php';

?>
<h2>Change Gades</h2>

<?php
    include 'db-connect.php';

    $grade_search = $_POST["grade_search"];
	$grade_name_search = $_POST["grade_name_search"];
    $change_grade = $_POST["change_grade"];

    /* variables for changing students grades */
    $new_grade = $_POST["new_grade"];
    $id = $_POST["id"];
    $dept = $_POST["dept"];
    $coursenum = $_POST["coursenum"];
    $semester = $_POST["semester"];
    $year = $_POST["year"];

    $fname = $_POST["fname"];
    $lname = $_POST["lname"];

    $_SESSION["student_id"]  = $id;
    /* runs if grade was changed */
    if($change_grade){
        $query = "UPDATE transcripts
            SET grade = '$new_grade'
            WHERE studentid = '$id' AND dept = '$dept' AND coursenum = '$coursenum' AND
            semester = '$semester' AND year = '$year';";

        if(strcmp($new_grade, "A") == 0 || strcmp($new_grade, "A-") == 0 || strcmp($new_grade, "B+") == 0 || strcmp($new_grade, "B") == 0 || strcmp($new_grade, "B-") == 0 || strcmp($new_grade, "C+") == 0 || strcmp($new_grade, "C") == 0 || strcmp($new_grade, "F") == 0) {
            $result = mysqli_query($connection, $query);
        } else {
            echo "Invalid grade <br/>";
        }
        $_SESSION["reload"] = 1;
        //echo "Sorry this isn't a valid student ID";
    }

	/* display page change a students grades */
	if($grade_name_search){
	   	/* get and display student name*/
		$query = "SELECT p.firstname, p.lastname
			FROM personalinfo p
			WHERE p.firstname = '$fname' AND p.lastname = '$lname';";

		$result = mysqli_query($connection, $query);
		if (mysqli_num_rows($result) == 0){
			echo "This student doesn't exist";
		}
		else{
			$row = mysqli_fetch_assoc($result);

			echo "<h2>".$row["firstname"]." ".$row["lastname"]."</h2>";

			/* get student grade information */
			$query = "SELECT t.studentid, t.dept, t.coursenum, t.grade, t.semester, t.year
				FROM transcripts t, personalinfo p
				WHERE p.firstname = '$fname'  AND p.lastname = '$lname' AND p.id = t.studentid
				ORDER BY t.year, t.semester DESC;";

    		$result = mysqli_query($connection, $query);

			/* display student's current grade information with option to change */
			if (mysqli_num_rows($result) > 0){
				echo "<table>";
				echo "<tr><th colspan=2>Course</th><th>Semester</th><th>Year</th><th>Grade</th><th></th></tr>";
				while ($row = mysqli_fetch_assoc($result)){
					echo "<tr>";

					echo "<td>".$row["dept"]."</td><td>".$row["coursenum"]."</td><td>".$row["semester"]."</td><td>".$row["year"]."</td>";
					echo "<td><form method='post' action='change-any-student-grades.php'>";
					echo "<input type='text' name='new_grade' value=".$row["grade"].">";
					echo "</td>";
					echo "<td><input type='submit' name='change_grade' value='Change'></td>";

					/* pass through info needed to change grade */
					echo "<input type='hidden' name='id' value=".$row["studentid"].">";
					echo "<input type='hidden' name='dept' value=".$row["dept"].">";
					echo "<input type='hidden' name='coursenum' value=".$row["coursenum"].">";
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
		$query = "SELECT p.firstname, p.lastname
			FROM personalinfo p
			WHERE p.id = '$id';";

		$result = mysqli_query($connection, $query);

		$row = mysqli_fetch_assoc($result);

		/* get student grade information */
		$query = "SELECT t.studentid, t.dept, t.coursenum, t.grade, t.semester, t.year
			FROM transcripts t
			WHERE t.studentid = '$id'
			ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($connection, $query);

		/* display student's current grade information with option to change */
		if (mysqli_num_rows($result) > 0){
			echo "<table>";
			echo "<tr><th colspan=2>Course</th><th>Semester</th><th>Year</th><th>Grade</th><th></th></tr>";
			while ($row = mysqli_fetch_assoc($result)){
				echo "<tr>";

				echo "<td>".$row["dept"]."</td><td>".$row["coursenum"]."</td><td>".$row["semester"]."</td><td>".$row["year"]."</td>";
				echo "<td><form method='post' action='change-any-student-grades.php'>";
				echo "<input type='text' name='new_grade' value='".$row["grade"]."'>";
				echo "</td>";
				echo "<td><input type='submit' name='change_grade' value='Change'></td>";

				/* pass through info needed to change grade */
				echo "<input type='hidden' name='id' value=".$row["studentid"].">";
				echo "<input type='hidden' name='dept' value=".$row["dept"].">";
				echo "<input type='hidden' name='coursenum' value=".$row["coursenum"].">";
				echo "<input type='hidden' name='semester' value=".$row["semester"].">";
				echo "<input type='hidden' name='year' value=".$row["year"]."></form>";

				echo "</tr>";
			}
			echo "</table>";

			$_SESSION["reload"] = 0;
		}
		else {
			echo "Sorry, this isn't a valid ID";
		}
	}

?>

</body>
</html>
