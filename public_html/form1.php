<?php 
	$allowed_user_types = array(
        "STUDENT"
 	);
	include 'header.php';
	include 'db-connect.php';
	$studentid = $_SESSION["id"];
	$student_info_query = "SELECT firstname, lastname
	FROM personalinfo WHERE id=$studentid;";
	$student_info_result = mysqli_query($connection, $student_info_query);
	$row = mysqli_fetch_assoc($student_info_result);
	$studentfirstname = $row['firstname'];
	$studentlastname = $row['lastname'];


	$faculty_id_query = "SELECT facultyid FROM advises WHERE studentid = $studentid;";
	$faculty_id_result = mysqli_query($connection, $faculty_id_query);
	$row = mysqli_fetch_assoc($faculty_id_result);
	$facultyid = $row['facultyid'];
	$faculty_advisor_query = "SELECT firstname, lastname FROM personalinfo 
	WHERE id = $facultyid;";
	$faculty_advisor_result = mysqli_query($connection, $faculty_advisor_query);
	$row = mysqli_fetch_assoc($faculty_advisor_result);
	$advisorfirstname = $row['firstname'];
	$advisorlastname = $row['lastname'];

?>

<html>
<head><title>Form 1</title></head>
<link rel="stylesheet" href="style.css">
<body>

<?php 

		echo "<h1>Form 1</h1>";


		$duplicate_query = "SELECT studentid FROM graduationapplication WHERE studentid=$studentid;";
		$result_from_query = mysqli_query($connection, $duplicate_query);

		$row = mysqli_fetch_assoc($result_from_query);


		if ($row['studentid']==$studentid){
				echo "You have already submitted an application for graduation.";
		}



		if(ISSET($_POST['formsubmitted'])){
			$degreename = $_POST['degreename'];
			for ($i = 1; $i <= 12; $i++){
				$courses[$i] = $_POST["course$i"];
			}
			$courses = array_unique($courses);
			// Checks if courses entered were taken 
			$course_count = 0;
			for ($i = 1; $i <= 12; $i++) {
				$course_check_query = "SELECT grade FROM transcripts, courses WHERE transcripts.studentid =$studentid AND transcripts.coursenum=courses.coursenum AND courses.courseid=$courses[$i] AND courses.dept = transcripts.dept;";
				$course_check_result = mysqli_query($connection, $course_check_query);
				$row = mysqli_fetch_assoc($course_check_result);
				if (ISSET($row['grade'])) {
					if (strcmp($row['grade'], 'IP') == 0) {
						$courses_in_progress = "<i>One or more of the courses you have entered are still in progress. Please check your transcript to make sure all grades have been submitted.</i>";
					}
					else {
						$course_count++;
					}
				}
				else if ($courses[$i] != 0){
					$courses_not_taken_query = "SELECT DISTINCT coursenum,dept FROM courses WHERE courseid=$course[$i];";
					$courses_not_taken_result = mysqli_query($connection,$courses_not_taken_query);
					$row = mysqli_fetch_assoc($courses_not_taken_result);
					$dept = $row['dept'];
					$coursenum = $row['coursenum'];
					$courses_not_taken .= $dept." ".$coursenum.", ";
				} 
			}
			// Checks that there are at least 10 courses. 
			if($course_count < 10) {
				$not_enough_courses = "<i>You have not taken enough courses. The mininum requirement for graduation is 10 courses.</i>";
			}
			// check against degree
			$core_courses_count = 0;
			for($i = 1; $i<=12; $i++){
				$degree_check_query = "SELECT degreename, courseid FROM degreerequirements WHERE degreename='$degreename';";
				$degree_check_result = mysqli_query($connection, $degree_check_query);
				while($row =mysqli_fetch_assoc($degree_check_result)){
					if (strcmp($courses[$i], $row['courseid']) == 0) {
						$core_courses_count++;
					}
				}
			}
			$num_core_courses_query = "SELECT COUNT(courseid) AS CourseCount FROM degreerequirements WHERE degreename='$degreename';";
			$num_core_courses_result = mysqli_query($connection, $num_core_courses_query);
			$row = mysqli_fetch_assoc($num_core_courses_result);
			$num_core_courses = $row['CourseCount'];
			// check core classes are satisfied
			if ($core_courses_count < $num_core_courses) {
				$core_courses_error = "<i>You have not taken one or more of the core courses required for your degree.</i>";
			}
			// Check to make sure GPA is > 3.0
			$gpa_calc_query = "SELECT (Sum(qualitypoints*credithours)/Sum(credithours)) AS GPA, transcripts.year FROM gradecalc, courses, transcripts WHERE gradecalc.grade = transcripts.grade AND transcripts.studentid=$studentid AND transcripts.coursenum = courses.coursenum;";
			$gpa_calc_result = mysqli_query($connection, $gpa_calc_query);
			if(mysqli_num_rows($gpa_calc_result)>0){
				while($row=mysqli_fetch_assoc($gpa_calc_result)){
					$year = $row['year'];
					if ($row['GPA'] < 3.0) {
						$gpa_error = "<i>You have not met the minimum GPA requirement of 3.0 required for your degree.</i>";
					}
					$gpaval = $row['GPA'];
				}
			}
			//check credit hours
			$credit_hours_query = "SELECT (Sum(credithours)) AS CREDITS FROM courses, transcripts WHERE transcripts.coursenum = courses.coursenum AND transcripts.studentid = $studentid;";
			$credit_hours_result = mysqli_query($connection, $credit_hours_query);
			if(mysqli_num_rows($credit_hours_result)>0){
				while($row=mysqli_fetch_assoc($credit_hours_result)){
					if($row['CREDITS'] < 30) {
						$credit_hours_error = "<i>You have not met the minimum requirement of 30 credit hours required for your degree.</i>";
					}
					$numcredits = $row['CREDITS'];
				}
			}
			//check if more than two grades below B-
			$letter_grade_check = 0;
			$course_grade_check_query = "SELECT qualitypoints FROM transcripts, gradecalc
			WHERE student=$studentid AND gradecalc.grade = transcripts.grade;";
			$course_grade_check_result = mysqli_query($connection, $course_grade_check_query);

			while ($row = mysqli_fetch_assoc($course_grade_check_result)) {
				if ($row['qualitypoints'] < 2.70) {
					$letter_grade_check++;
				}
			}
			if ($letter_grade_check > 2) {
				$grades_error = "<i>You have more than two letter grades below B-.</i>";
			}

			if(($letter_grade_check < 2) && ($numcredits>=30) && ($gpaval>=3.0) && ($core_courses_count==$num_core_courses) && ($course_count>=10)){
				// actually insert the application into the database
				for ($i = 1; $i <= 12; $i++) {
					if ($courses[$i] > 0) {
						$form_insert_query = "INSERT INTO graduationapplication(studentid,courseid,year) VALUES($studentid,$courses[$i],'$year')";
						$form_insert_result = mysqli_query($connection, $form_insert_query);
					}
				}
				// Query to update students cleared field to 1 if all conditions met 
				$cleared_query = "UPDATE graduationapplication SET cleared = 1 WHERE studentid = '$studentid';";
				$result_cleared_query = mysqli_query($connection, $cleared_query);

				if ($result_cleared_query) {
					$applicationclearedsucessfully = "Application cleared successfully!";
				}
			}
		}
	if ($applicationclearedsucessfully){
		echo $applicationclearedsucessfully;
	}

	else {
		if(($not_enough_courses)||($courses_in_progress)||($courses_not_taken)||($core_courses_error)||($gpa_error)||($credit_hours_error)||($grades_error)){
			echo "<b>Application failed to be cleared for graduation because: </b><br>";
			
			if($not_enough_courses){
				echo $not_enough_courses;
				echo "<br>";
			}
			if($courses_in_progress){
				echo $courses_in_progress;
				echo "<br>";
			}
			if($courses_not_taken){
				echo "<i>You have not taken the following courses:</i> ";
				echo $courses_not_taken;
				echo "<br>";
			}
			if($core_courses_error){
				echo $core_courses_error;
				echo "<br>";
			}
			if($gpa_error){
				echo $gpa_error;
				echo "<br>";
			}
			if($credit_hours_error){
				echo $credit_hours_error;
				echo "<br>";
			}
			if($grades_error){
				echo $grades_error;
				echo "<br>";
			}
		}
		echo "
		<form method='post'>
		<p><b>First Name:</b> ".$studentfirstname." </p>
		<p><b>Last Name:</b> ".$studentlastname."</p>
		<p><b>Student ID:</b> ".$studentid." </p>
		<p><b>Enter Degree:</b> </p>
		<select name='degreename'>";
		$degree_query = "SELECT DISTINCT degreename FROM degreerequirements, courses WHERE degreerequirements.courseid = courses.courseid;";
		$degree_result = mysqli_query($connection, $degree_query);
		while($row=mysqli_fetch_assoc($degree_result)){
			echo "<option value='".$row['degreename']."'>";
			echo $row['degreename'];
			echo "</option>";
		}
		echo "</select>

		<p><b>Faculty Advisor:</b> ".$advisorfirstname." ".$advisorlastname."</p>
		<h3>Courses Taken<br></h3>
		<p>Please enter all of the courses that you have taken.</p>";
		for ($i = 1; $i <= 12; $i++) {
			echo "<select name ='course$i'>";
			echo "<option value = '0'>-----</option>";
			$courses_query = "SELECT dept,coursenum, courseid FROM courses;";
			$courses_result = mysqli_query($connection, $courses_query);
			while ($row = mysqli_fetch_assoc($courses_result)) {
				$coursenum = $row['coursenum'];
				$courseid = $row['courseid'];
				$dept = $row['dept'];
				echo "<option value='$courseid'>".$dept." ".$coursenum."</option>";
			}
			echo "</select>";
			echo "<br><br>";
		}
		echo"<br>
		<input type='hidden' name='formsubmitted'>
		<input type='submit' value='Apply To Graduate'>";
	}
	



echo "</body>";
echo "</html>";
?>
