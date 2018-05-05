<?php 
	include 'header.php';
	include 'db-connect.php';
	$studentid = $_SESSION['id'];
?>
	<head><title>Advising Form</title></head>
	<link rel='stylesheet' href='style.css'>
	<body>
<?php
	$student_query = "SELECT firstname, lastname FROM personalinfo WHERE id=$studentid;";
	$student_result = mysqli_query($connection, $student_query);
	$row = mysqli_fetch_assoc($student_result);
	$studentfirstname = $row['firstname'];
	$studentlastname = $row['lastname'];

	$faculty_id_query = "SELECT DISTINCT facultyid FROM advises WHERE studentid= $studentid;";
	$faculty_id_result = mysqli_query($connection, $faculty_id_query);
	$row = mysqli_fetch_assoc($faculty_id_result);
	$faculty_id = $row['facultyid'];
	$faculty_advisor_query = "SELECT firstname, lastname FROM personalinfo 
	WHERE id = $faculty_id;";
	$faculty_advisor_result = mysqli_query($connection, $faculty_advisor_query);
	$row = mysqli_fetch_assoc($faculty_advisor_result);
	$advisorfirstname = $row['firstname'];
	$advisorlastname = $row['lastname'];


	$degree_name_query = "SELECT DISTINCT degreename FROM advises WHERE studentid=$studentid;";
	$degree_name_result = mysqli_query($connection, $degree_name_query);
	$row = mysqli_fetch_assoc($degree_name_result);
	$degreename = $row['degreename'];

	$complete = $_POST['complete'];

	// Puts courses entered into an array 
	if($complete){
		for($i = 1; $i <= 12; $i++) {
			$courses[$i] = $_POST["course$i"];
		}

		// Removes duplicate courses from array 
		$courses = array_unique($courses);

		// Counts number of core courses entered into form 
		$core_course_count = 0;
		for($i = 1; $i <= 12; $i++) {
			$core_courses_query = "SELECT courseid FROM degreerequirements WHERE degreename='$degreename';";
			$core_courses_result = mysqli_query($connection, $core_courses_query);
			while($row=mysqli_fetch_assoc($core_courses_result)){
				$courseid = $row['courseid'];
				if($courses[$i]==$courseid){
					$core_course_count++;
				}
			}
		}
		if($core_course_count < 3){
			$core_course_error = "Not all core courses have been entered. Please check your degree requirements and make sure you have entered all of the core courses.";
		}
		// Counts total number of courses entered into form 
		$total_course_count = 0;
		for($i = 1; $i <= 12; $i++){
			if($courses[$i]>0){
				$total_course_count++;
			}
		}
		// Makes sure there are 10 courses entered in form 
		if($total_course_count<10){
			$total_course_error = "Less than 10 courses have been entered. Please enter 10 unique courses into the advising form including the three core courses specified in your degree requirements.";
		
		}
		//Inserts courses into new studentadvisingform table and counts number of successful inserts
		else{
				$successful_insert_count = 0;
				for($i=1; $i<=12; $i++){
					if($courses[$i]>0){
						$advising_form_insert_query = "INSERT INTO newstudentadvisingform(studentid,courseid,facultyid) VALUES($studentid,$courses[$i],$faculty_id);";
						$advising_form_insert_result = mysqli_query($connection,$advising_form_insert_query);
						if($advising_form_insert_result){
							$successful_insert_count++;
						}
					}
				}
				if($successful_insert_count==$total_course_count){
					$advising_form_submitted = "Advising form successfully submitted! Once your faculty advisor signs off on this form, your registration hold will be lifted off of your acccount.";
				}
		}
	}




	// Checks if student has already submitted form 
	$advising_form_query = "SELECT hold FROM newstudentadvisingform WHERE studentid=$studentid;";
	$advising_form_result = mysql_query($connection,$adivising_form_query);
	$row1 = mysqli_fetch_assoc($advising_form_result);

	// Checks if student has new student hold on account 
	$check_hold_query = "SELECT hold FROM advises WHERE hold='New Student' AND studentid=$studentid;";
	$check_hold_result = mysqli_query($connection,$check_hold_query);
	$row = mysqli_fetch_assoc($check_hold_result);

	echo "<h1>New Student Advising Form</h1>";

	if(ISSET($row1['studentid'])){
		echo "You have already submitted an advising form. Please wait for your advisor to sign off on your form 
		to lift your registration hold.";
	}
	else if($row['hold']!='New Student'){
		echo "There is not a new student registration hold on your account. You do not have access to this form because your advisor has already lifted the hold on your account.";
	}
	else if($advising_form_submitted){
		echo $advising_form_submitted;
	}
	else {
		echo "<p>This form must be filled out to lift the initial registration hold off of your account.</p>
		<form method='post'>
		<p><b>First Name:</b> ".$studentfirstname."</p>
		<p><b>Last Name:</b> ".$studentlastname."</p>";
		$degrees_query = "SELECT DISTINCT degreename FROM degreerequirements;";
		$degrees_result = mysqli_query($connection, $degrees_query);
		echo "<p><b>Degree:</b> ".$degreename."</p>
		<p><b>Student ID:</b> ".$studentid."</p>
		<p><b>Faculty Advisor:</b> ".$advisorfirstname." ".$advisorlastname."</p>
		<h3>Enter Planned Courses<br></h3>
		<p>Please enter the courses you plan to take. You must take a minimum of 10 courses including the three core courses required for your degree. These courses can be found on the view degree requirements page inn the main menu. Once you submit this form, your faculty advisor will sign off on this form electronically and the registration hold on your account will be lifted.</p>";
		for($i = 1; $i <= 12; $i++){
			echo "<select name ='course$i'>
			<option value = '0'>-----</option>";
			$courses_query = "SELECT dept,coursenum,courseid FROM courses;";
			$courses_result = mysqli_query($connection, $courses_query);
			while($row = mysqli_fetch_assoc($courses_result) ){
				$coursenum = $row['coursenum'];
				$courseid = $row['courseid'];
				$dept = $row['dept'];
				echo "<option value='$courseid'>".$dept." ".$coursenum."</option>";
			}
			echo "</select>
			<br><br>";
		}
			if($core_course_error){
				echo $core_course_error;
				echo "<br>";
			}
			else if($total_course_error){
				echo $total_course_error;
				echo "<br>";
			}
			echo "<input type='hidden' name='complete' value='complete'>
			<input type='submit' value='Submit Form'>";
	}





	echo "</body>";
	echo "</html>";
?>