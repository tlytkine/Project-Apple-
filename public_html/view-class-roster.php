<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>View Rosters</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
$allowed_user_types = array(
    "INSTRUCTOR"
);
include 'header.php';

?>
<h2>View Class Rosters</h2>

<?php
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

        /* determine if grade was changed */
        $change_grade = $_POST["change_grade"];

        /* variables for changing students grades */
    	$new_grade = $_POST["new_grade"];
    	$id = $_POST["id"];
    	$dept = $_POST["dept"];
    	$coursenum = $_POST["coursenum"];
    	$semester = $_POST["semester"];
    	$year = $_POST["year"];

        /* runs if grade was changed */
        if($change_grade){
    		$query = "UPDATE transcripts
    			SET grade = '$new_grade'
    			WHERE studentid = '$id' AND dept = '$dept' AND coursenum = '$coursenum' AND
    			semester = '$semester' AND year = '$year';";

    		if(strcmp($new_grade, "A") == 0 || strcmp($new_grade, "A-") == 0 || strcmp($new_grade, "B+") == 0 || strcmp($new_grade, "B") == 0 || strcmp($new_grade, "B-") == 0 || strcmp($new_grade, "C+") == 0 || strcmp($new_grade, "C") == 0 || strcmp($new_grade, "F") == 0) {
    			$result = mysqli_query($conn, $query);
    		} else {
    			echo "Invalid grade <br/>";
    		}

    		//echo "Sorry this isn't a valid student ID";
    	}

        $query = "SELECT p.id, p.firstname, p.lastname, t.dept, t.coursenum, c.semester, c.year, t.grade
				FROM personalinfo p, transcripts t, courses c, users u
				WHERE p.id = t.studentid AND u.id = t.professorid AND u.email = '$_SESSION[email]'
						AND c.semester = t.semester AND c.year = t.year AND t.coursenum = c.coursenum AND t.dept = c.dept;";

		$result = mysqli_query($connection, $query);

		/* display class rosters */
		if (mysqli_num_rows($result) > 0){
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
				echo "<form method='post' action='view-class-roster.php'>";
				echo "<input type='text' name='new_grade' value=".$row["grade"].">";
				echo "</td>";
				echo "<td><input type='submit' name='change_grade' value='Change'></td>";
				echo "</tr>";

				/* pass through info needed to change grade */
				echo "<input type='hidden' name='sid' value=".$row["id"].">";
				echo "<input type='hidden' name='dept' value=".$row["dept"].">";
				echo "<input type='hidden' name='cid' value=".$row["coursenum"].">";
				echo "<input type='hidden' name='semester' value=".$row["semester"].">";
				echo "<input type='hidden' name='year' value=".$row["year"]."></form>";
			}
			echo "</table>";
		}
		else {
			echo "You don't have anyone registered for your classes currently";
		}
?>

</body>
</html>
