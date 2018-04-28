<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>View Transcript</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
$allowed_user_types = array(
    "GS",
    "ADMIN"
);
include 'header.php';

?>
<h2>View Transcript</h2>

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

    $transcript_search = $_POST["transcript_search"];
	$transcript_name_search = $_POST["transcript_name_search"];

    /* variables for displaying transcript */
	$student_id = $_POST["id"];
	$fname = $_POST["fname"];
	$lname = $_POST["lname"];

    /* display student's transcript using the name as the input*/
	if($transcript_name_search){
		/* get and display student name*/
		$query = "SELECT p.firstname, p.lastname
			FROM personalinfo p
			WHERE p.firstname = '$fname' AND p.lastname = '$lname';";

		$result = mysqli_query($connection, $query);

		$row = mysqli_fetch_assoc($result);

		$user_exists = 0;
		if (mysqli_num_rows($result) > 0){
			echo "<h2>".$row["firstname"]." ".$row["lastname"]."</h2>";
			$user_exists = 1;
		}

		/* get transcript information */
		$query = "SELECT t.dept, t.coursenum, c.credithours, t.grade, t.year, t.semester, t.title
			FROM transcripts t, courses c, personalinfo p
			WHERE t.coursenum = c.coursenum AND t.dept = c.dept AND
			p.firstname = '$fname' AND p.lastname = '$lname' AND t.studentid = p.id
			ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($connection, $query);

		$total_credits = 0;
		$weight = 0;
		$sum = 0;

		/* display transcript information */
		if (mysqli_num_rows($result) > 0){
			echo "<table>";
			$cur_year = ""; //track current year
			$cur_sem = ""; //track current semester
			while ($row = mysqli_fetch_assoc($result)){
				if($cur_year != $row["year"] || $cur_sem != $row["semester"]){
					echo "</table><br><table>";
					echo "<tr><th colspan=2>Course</th><th>Title</th><th>Credits</th><th>Grade</th><th>Semester</th><th>Year</th></tr>";
					$cur_year = $row["year"];
					$cur_sem = $row["semester"];
				}
				echo "<tr>";

				echo "<td>".$row["dept"]."</td>";
				echo "<td>".$row["coursenum"]."</td>";
                echo "<td>".$row["title"]."</td>";
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
		else if ($user_exists == 1){
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

		$result = mysqli_query($connection, $query);

		$row = mysqli_fetch_assoc($result);

		$user_exists = 0;
		if (mysqli_num_rows($result) > 0){
			echo "<h2>".$row["firstname"]." ".$row["lastname"]."</h2>";
			$user_exists = 1;
		}

		/* get transcript information */
		$query = "SELECT t.dept, t.coursenum, c.credithours, t.grade, t.year, t.semester, t.title
			FROM transcripts t, courses c
			WHERE t.coursenum = c.coursenum AND t.dept = c.dept AND
			t.studentid = '$student_id'
			ORDER BY t.year, t.semester DESC;";

		$result = mysqli_query($connection, $query);

		$total_credits = 0;
		$weight = 0;
		$sum = 0;

		/* display transcript information */
		if (mysqli_num_rows($result) > 0){
			echo "<table>";
			$cur_year = ""; //track current year
			$cur_sem = ""; //track current semester
			while ($row = mysqli_fetch_assoc($result)){
				if($cur_year != $row["year"] || $cur_sem != $row["semester"]){
					echo "</table><br><table>";
					echo "<tr><th colspan=2>Course</th><th>Title</th><th>Credits</th><th>Grade</th><th>Semester</th><th>Year</th></tr>";
					$cur_year = $row["year"];
					$cur_sem = $row["semester"];
				}
				echo "<tr>";

				echo "<td>".$row["dept"]."</td>";
				echo "<td>".$row["coursenum"]."</td>";
                echo "<td>".$row["title"]."</td>";
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
		else if ($user_exists == 1){
			echo "This student has not taken any of your classes yet";
		}
		else {
			echo "Sorry that is not a valid student ID";
		}
	}

?>

</body>
</html>
