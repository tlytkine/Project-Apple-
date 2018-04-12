<?php
	// Performs an audit on a submitted application
	// login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role = 'STUDENT'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	}
?>

<html>
<head>
<title>
ADS
</title>
</head>
<link rel ="stylesheet" type="text/css" href="style1.css"/>
<body>
<h1>ADS</h1>

<?php
// Set variables
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$gwid = $_POST['gwid'];
$degree = $_POST['degree'];

// Load all given courses
for ($i = 1; $i <= 12; $i++) {
	$courses[$i] = $_POST["course$i"];
}

// Display some info
echo "First Name: " . $first_name . "<br>";
echo "Last Name: " . $last_name . "<br>";

// Connect to database
$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");
//Check	connection
if (!$conn)	{	
	die("Connection failed: " . mysqli_connect_error());	
}		

// check if user already has applied
$duplicate_query = "SELECT * FROM applications WHERE gwid='$gwid';";
$result_from_query=mysqli_query($conn, $duplicate_query);
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
	$course_query = "SELECT * FROM course_status 
	WHERE gwid ='$gwid' AND coursenum='$courses[$i]';";
	$result_from_query = mysqli_query($conn, $course_query);
	$row = mysqli_fetch_assoc($result_from_query);

	if (isset($row['grade'])) {
		if (strcmp($row['grade'], 'IP') == 0) {
			echo "$course_query <br>";
			echo "course in progress <br><br>";
		}
		else {
			$course_count++;
		}
	}
	else if ($courses[$i] != 0){
		echo "course not taken: $courses[$i]<br>";
	}
}
echo "<br>";
// make sure there are ten coureses
if ($course_count < 10) {
	$error .= "Not enough classes to graduate<br>";
}

// check against degree
$degree_query = "SELECT * FROM degrees WHERE degree_name='$degree';";
$result_from_query = mysqli_query($conn, $degree_query);
$row = mysqli_fetch_assoc($result_from_query);

$coure_courses_count = 0;
for ($i = 1; $i < 12; $i++) {
	if (strcmp($courses[$i], $row['core1']) == 0 ||
		strcmp($courses[$i], $row['core2']) == 0 ||
		strcmp($courses[$i], $row['core3']) == 0 ) {
		$coure_courses_count++;
	}
}

// check core classes are satisfied
if ($coure_courses_count != 3) {
	$error .= "Degree core courses are not satisfied<br><br>";
}

// check gpa
$gpa_calc = "SELECT (Sum(qualitypoints*credithours)/Sum(credithours)) AS GPA 
FROM grade_calc, courses, course_status
WHERE grade_calc.grade = course_status.grade 
AND course_status.gwid = '$gwid'
AND course_status.coursenum = courses.coursenum;";

$gpa_calc_result = mysqli_query($conn, $gpa_calc);

if(mysqli_num_rows($gpa_calc_result)>0){
	while($row=mysqli_fetch_assoc($gpa_calc_result)){
		if ($row['GPA'] < 3.0) {
			$error .= 'Not a high enough GPA<br>';
		}
	}
}

//check credit hours
$credits_calc = "SELECT (Sum(credithours)) AS CREDITS
FROM courses, course_status
WHERE course_status.coursenum = courses.coursenum
AND course_status.gwid = '$gwid';";

$credit_calc_result = mysqli_query($conn, $credits_calc);

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
FROM course_status, grade_calc 
WHERE gwid='$gwid' AND grade_calc.grade = course_status.grade;";
$course_grade_result = mysqli_query($conn, $course_grade_check);

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
	mysqli_close($conn);
}

// actually insert the application into the database
for ($i = 1; $i <= 12; $i++) {
	if ($courses[$i] > 0) {
		$form_insert = "INSERT INTO applications(firstname,lastname,GWID,crn)
		VALUES ('$first_name','$last_name','$gwid','$courses[$i]');";

		$result_form_insert = mysqli_query($conn, $form_insert);

		if($result_form_insert){
			echo "Course $i added successfully!<br>\n";
		}
		else {
			echo "Error: " . $form_insert . "<br>" . mysqli_error($conn);
		}
	}
}

// Query to update students cleared field to 1 if all conditions met 
$cleared_query = "UPDATE students
				SET students.cleared = 1 
				WHERE students.gwid = '$gwid';";
$result_cleared_query = mysqli_query($conn, $cleared_query);

// close the connection
mysqli_close($conn);
?>
	<br>
	<b><a href="logout.php">Log Out</a></b>
</body>

</html>