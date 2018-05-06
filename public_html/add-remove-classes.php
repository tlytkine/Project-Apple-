<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>Add/Remove Courses Offered</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
    $allowed_user_types = array(
        "ADMIN"
    );
    include 'header.php';
    include 'db-connect.php';

    echo "<h1>Add/Remove Courses Offered</h1>";

    /* used for page display */
	$add_class_info = $_POST["add_class_info"];
	$add_class = $_POST["add_class"];
	$remove_class = $_POST["remove_class"];

    $new_class_crn = $_POST["new_class_crn"];
    $remove_class_crn = $_POST["remove_class_crn"];

	if($remove_class){
		$query = "DELETE FROM prereqs
					WHERE '$remove_class_crn' = courseid;";
		$result = mysqli_query($connection, $query);

		$query = "DELETE FROM courses
					WHERE '$remove_class_crn' = courseid;";
		$result = mysqli_query($connection, $query);
		if(mysqli_affected_rows($connection) > 0){
			echo "<h4>Class Removed</h4>";
		}
		else{
			echo "<h4>Invalid CRN</h4>";
		}
	}

	else if($add_class_info){
		$query = "SELECT courseid
					FROM courses
					WHERE $new_class_crn = courseid;";
		$result = mysqli_query($connection, $query);

		if (!is_numeric($new_class_crn)){
			echo "<h4>Invalid CRN</h4>";
		}
		else if (mysqli_num_rows($result) > 0){
			echo "<h4>CRN already in use</h4>";
		}
		else{
			echo '<form method="post" action="add-remove-classes.php">';
			echo '<h4>Input Class Info</h4>';

			echo '<label for=new_class_crn>courseid: </label>';
			echo "<input type=text name=new_class_crn value='$new_class_crn'><br>";

			echo '<label for=new_class_dept>Department: </label>';
			echo '<input type="text" name="new_class_dept"><br>';

			echo '<label for=new_class_cid>coursenum: </label>';
			echo '<input type="text" name="new_class_cid"><br>';

			echo '<label for=new_title>Title: </label>';
			echo '<input type="text" name="new_title"><br>';

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

	else if($add_class){
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
		$new_title = $_POST["new_title"];
		$new_year = "";
		$new_semester = "";

		$valid = 1;
		/* check department */
		$new_dept = strtoupper($new_dept);
		if(strcmp($new_dept, "CS") != 0 && strcmp($new_dept, "EE") != 0 && strcmp($new_dept, "MATH") != 0){
			echo "Invalid Department<br />";
			$valid = 0;
		}

		/* check coursenum */
		if($valid == 0){
			echo "Can't check coursenum because of invalid department<br />";
		}
		else if (!is_numeric($new_cid) || $new_cid < 0 || $new_cid > 9999){
			echo "Invalid coursenum<br />";
			$valid = 0;
		}

		/* check section */
		if($valid == 0){
			echo "Can't check Section because of invalid department or coursenum<br />";
		}
		else if (!is_numeric($new_section)){
			echo "Invalid Section<br />";
		}
		else{
			$query = "SELECT c.dept, c.coursenum, c.section
						FROM courses c
						WHERE c.dept = '$new_dept' AND c.coursenum = '$new_cid' AND c.section = '$new_section';";
			$result = mysqli_query($connection, $query);

			if (mysqli_num_rows($result) > 0){
				echo "Duplicate Course<br />";
				$valid = 0;
			}
		}

		/* check pid */
		$query = "SELECT p.id
					FROM personalinfo p, roles r
					WHERE $new_pid = p.id AND r.id = p.id AND r.role = 'INSTRUCTOR';";
		$result = mysqli_query($connection, $query);

		if ($result == NULL || mysqli_num_rows($result) <= 0){
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

		/* check first prereq */
		if($new_prereq1 != NULL){
			$query = "SELECT courseid
						FROM courses
						WHERE courseid = '$new_prereq1';";
			$result = mysqli_query($connection, $query);

			if (mysqli_num_rows($result) <= 0){
				echo "Invalid prereq1 CRN<br />";
				$valid = 0;
			}
		 }

		 /* check second prereq */
		 if($new_prereq2 != NULL){
			 $query = "SELECT courseid
						 FROM courses
						 WHERE courseid = '$new_prereq2';";
			 $result = mysqli_query($connection, $query);

			 if (mysqli_num_rows($result) <= 0){
				 echo "Invalid prereq2 CRN<br />";
				 $valid = 0;
			 }
		 }

		/* if input is valid add to database */
		if($valid == 1){
			/* get current semester and year */
			$query = "SELECT c.year, c.semester
						FROM courses c
						LIMIT 1;";
			$result = mysqli_query($connection, $query);

			$row = mysqli_fetch_assoc($result);

			$new_year = $row["year"];
			$new_semester = $row["semester"];

			/* add class to database */
			$query = "INSERT INTO courses
						VALUES ('$new_crn', '$new_dept', '$new_cid', '$new_section', '$new_title',
							 	'$new_credits', '$new_day', '$new_time', '$new_year',
								'$new_semester', '$new_pid');";
			$result = mysqli_query($connection, $query);
			if(mysqli_affected_rows($connection) > 0){
				echo "<h4>Class Added</h4>";
			}
			else{
				echo "<h4>Query error</h4>";
			}

			/* add prereq1 */
			if($new_prereq1 != NULL){
				$query = "INSERT INTO prereqs
							VALUES ('$new_crn', '$new_prereq1');";
				$result = mysqli_query($connection, $query);
			}

			/* add prereq1 */
			if($new_prereq2 != NULL){
				$query2 = "SELECT prereqid
							FROM prereqs
							WHERE prereqid = '$new_prereq2' AND courseid = '$new_crn';";
				$result2 = mysqli_query($connection, $query2);

				if(mysqli_num_rows($result2) > 0){
   					echo "Duplicate Prereq 2<br />";
   					echo "Will only use once<br />";
   			 	}
				else{
					$query = "INSERT INTO prereqs
								VALUES ('$new_crn', '$new_prereq2');";
					$result = mysqli_query($connection, $query);
				}
			}
		}
		else{
			echo "Try Again<br />";
		}
	}
    else {
		echo '<form method="post" action="add-remove-classes.php">';
		echo '<h4>Enter course id to add class</h4>';
		echo '<input type="text" name="new_class_crn"><br><br>';
		echo '<input type="submit" name="add_class_info" value="Add">';

		echo '<h4>Enter course id to remove class</h4>';
		echo '<input type="text" name="remove_class_crn"><br><br>';
		echo '<input type="submit" name="remove_class" value="Remove">';
		echo '</form>';

		$query = "SELECT c.courseid, c.dept, c.coursenum, c.section, p.firstname, p.lastname, c.year, c.semester, c.credithours, c.day, c.time
			FROM courses c, personalinfo p
			WHERE c.professorid = p.id
			ORDER BY c.courseid;";
		$result = mysqli_query($connection, $query);

		echo "<h2> Current Classes </h2>";

		if (mysqli_num_rows($result) > 0) {
			echo "<table>";
			echo "<tr><th>Course ID</th><th colspan=2>Course</th><th>Section</th><th colspan=2>Professor</th><th>Year</th><th>Semester</th><th>Credits</th><th>Day</th><th>Time</th><th>Prereq1 CRN</th><th>Prereq2 CRN</th>";

			while($row = mysqli_fetch_assoc($result)) {

				echo "<tr>";
				echo  "<td>".$row["courseid"]."</td><td>".$row["dept"]."</td><td>".$row["coursenum"]."</td><td>".$row["section"]."</td><td>".$row["firstname"]."</td><td>".$row["lastname"]."</td>
						<td>".$row["year"]."</td><td>".$row["semester"]."</td><td>".$row["credithours"]."</td><td>".$row["day"]."</td><td>".$row["time"]."</td>";

				/* get any prereqs */
				$query = "SELECT * FROM prereqs
							WHERE courseid = '".$row["courseid"]."';";
				$prereq = mysqli_query($connection, $query);
				$prereq1CRN = mysqli_fetch_assoc($prereq);
				$prereq2CRN = mysqli_fetch_assoc($prereq);


				echo "<td>".$prereq1CRN["prereqid"]."</td><td>".$prereq2CRN["prereqid"]."</td>";

				echo "</tr>";
			}
			echo "</table>";

		}
	}

?>

</body>
</html>
