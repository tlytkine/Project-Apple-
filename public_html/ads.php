<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];

?>

<html>
<head><title>ADS</title></head>
<link rel="stylesheet" href="style.css">
<body>
<h1>ADS</h1><br> 
<?php



// Set variables
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$studentid = $_POST['studentid'];
$degreename = $_POST['degreename'];

// Load all given courses
for ($i = 1; $i <= 12; $i++) {
	$courses[$i] = $_POST["course$i"];
}

// Display some info
echo "First Name: " . $first_name . "<br>";
echo "Last Name: " . $last_name . "<br>";
echo "Student ID: " . $studentid . "<br>";
echo "Degree Name: " . $degreename . "<br>";

// check if user already has applied
$duplicate_query = "SELECT studentid FROM graduation_application WHERE studentid='$studentid';";
$result_from_query=mysqli_query($connection, $duplicate_query);
if(!$result_from_query){
	echo "Error: " . $result_from_query . "<br>";
}

if (mysqli_num_rows($result_from_query) > 0) {
	echo "Already applied<br>";
	exit();
}

$error = '';

// Remove any duplicate courses
$courses = array_unique($courses);

// check if user has actually taken courses
$course_count = 0;
for ($i = 1; $i <= 12; $i++) {
	// check is course is actually taken
	$course_query = "SELECT grade FROM transcripts, courses WHERE transcripts.studentid ='$studentid' AND transcripts.coursenum=courses.coursenum AND courses.courseid='$courses[$i]' AND courses.dept = transcripts.dept;";
	$result_from_query = mysqli_query($connection, $course_query);
	$row = mysqli_fetch_assoc($result_from_query);

	if (isset($row['grade'])) {
		if (strcmp($row['grade'], 'IP') == 0) {
			echo "$course_query <br>";
			echo "Course is still in progress.<br><br>";
		}
		else {
			$course_count++;
		}
	}
	else if ($courses[$i] != 0){
		echo "Course has not been taken: $courses[$i]<br>";
	}
}
echo "$course_count: " . $course_count . "<br>";
echo "<br>";
// make sure there are ten courses
if ($course_count < 10) {
	$error .= "Not enough classes to graduate<br>";
}

// check against degree
$degree_query = "SELECT degreename, courseid FROM degreerequirements WHERE degree_name='$degree';";
$result_from_query = mysqli_query($connection, $degree_query);

$core_courses_count = 0;
	$i=0;
	while($row =mysqli_fetch_assoc($result_from_query)){
		for($i = 1; $i<=12; $i++){
			if (strcmp($courses[$i], $row['courseid']) == 0) {
				$core_courses_count++;
			}
		}
	}
	echo "$core_courses_count: " . $core_courses_count . "<br>";

// check core classes are satisfied
if ($core_courses_count != 3) {
	$error .= "Degree core courses are not satisfied<br><br>";
}



// check gpa

$gpa_calc = "SELECT (Sum(qualitypoints*credithours)/Sum(credithours)) AS GPA, transcripts.year 
FROM gradecalc, courses, transcripts 
WHERE gradecalc.grade = transcripts.grade 
AND transcripts.studentidid = '$studentid'
AND transcripts.coursenum = courses.coursenum;";

$gpa_calc_result = mysqli_query($connection, $gpa_calc);

$year;
if(mysqli_num_rows($gpa_calc_result)>0){
	while($row=mysqli_fetch_assoc($gpa_calc_result)){
		$year = $row['year'];
		if ($row['GPA'] < 3.0) {
			$error .= 'Not a high enough GPA<br>';

		}
	}
}


//check credit hours
$credits_calc = "SELECT (Sum(credithours)) AS CREDITS
FROM courses, transcripts 
WHERE transcripts.coursenum = courses.coursenum
AND transcripts.studentid = '$studentid';";

$credit_calc_result = mysqli_query($connection, $credits_calc);

if (mysqli_num_rows($credit_calc_result)>0){
	while($row=mysqli_fetch_assoc($credit_calc_result)){
		if($row['CREDITS'] < 30) {
			$error .= 'Not enough credits<br>';
		}
	}
}

//check if more than two grades below B-
$letter_grade_check = 0;

$course_grade_check = "SELECT qualitypoints
FROM transcripts, gradecalc
WHERE student='$studentid' AND gradecalc.grade = transcripts.grade;";
$course_grade_result = mysqli_query($connection, $course_grade_check);

while ($row = mysqli_fetch_assoc($course_grade_result)) {
	if ($row['qualitypoints'] < 2.70) {
		$letter_grade_check++;
	}
}

if ($letter_grade_check > 2) {
	$error .= 'More than 2 grades below B-<br>';
}

if (strlen($error) != 0) {
	echo $error;
	mysqli_close($connection);
}



// actually insert the application into the database
for ($i = 1; $i <= 12; $i++) {
	if ($courses[$i] > 0) {
		$form_insert = "INSERT INTO graduationapplication(studentid,courseid,year)
		VALUES ($studentid,$courses[$i],'2018')";

		$result_form_insert = mysqli_query($connection, $form_insert);

		if(mysqli_affected_rows($result_form_insert) > 0){
			echo "Course $i added successfully!<br>\n";
		}
		else {
			echo "Error: " . $form_insert . "<br>" . mysqli_error($connection);
		}
	}
}

// Query to update students cleared field to 1 if all conditions met 
$cleared_query = "UPDATE graduationapplication
				SET cleared = 1 
				WHERE studentid = '$studentid';";
$result_cleared_query = mysqli_query($connection, $cleared_query);

// close the connection
mysqli_close($connection);
?>

</body>

</html>