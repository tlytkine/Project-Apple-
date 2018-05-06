<?php 
	$allowed_user_types = array(
        "ADVISOR"
 	);
	include 'header.php';
	include 'db-connect.php';
	$facultyid = $_SESSION["id"];
?>
	<head><title>Lift New Student Holds</title></head>
	<link rel='stylesheet' href='style.css'>
	<body>
<?php



	$lifthold = $_POST['lift_hold'];
	$viewform = $_POST['view_form'];

	$advisor_name_query = "SELECT firstname,lastname FROM personalinfo WHERE id=$facultyid;";
	$advisor_name_result = mysqli_query($connection,$advisor_name_query);

	$row = mysqli_fetch_assoc($advisor_name_result);
	$advisorfirstname = $row['firstname'];
	$advisorlastname = $row['lastname'];

	echo "<h1>Lift New Student Holds</h1>";

	$new_student_check_query = "SELECT studentid FROM advises WHERE advises.facultyid = $facultyid AND advises.hold = 'New Student';";
	$new_student_check_result = mysqli_query($connection,$new_student_check_query);
	$row = mysqli_fetch_assoc($new_student_check_result);


	if(ISSET(row['studentid'])){
		echo"<table>
		<tr>
		<th>Advisee</th>
		<th>Student ID</th>
		<th>Degree Name</th>
		<th>Admit Year</th> 
		<th>Review Advising Form</th>
		</tr>";
		$advisee_info_query = "SELECT firstname, lastname, studentid, degreename, admityear, hold FROM advises, personalinfo WHERE advises.facultyid=$facultyid AND advises.hold='New Student' AND advises.studentid = personalinfo.id;";
		$advisee_info_result = mysqli_query($connection,$advisee_info_query);
		while($row=mysqli_fetch_assoc($advisee_info_result)){
			echo "<tr>
			<td>".$row['firstname']." ".$row['lastname']."</td>
			<td>".$row['studentid']."</td>
			<td>".$row['degreename']."</td>
			<td>".$row['admityear']."</td>
			<td><form method='post'>
			<input type='hidden' name='studentid' value='".$row['studentid']."'>
			<input type='hidden' name='studentfirstname' value='".$row['firstname']."'>
			<input type='hidden' name='studentlastname' value='".$row['lastname']."'>
			<input type='submit' value='View Form' name='view_form'>
			</form></td>
			</tr>";
		}
		echo "</table>";

		if($form_not_submitted){
			echo $form_not_submitted;
		}
	}
	else {
		echo "There are currently no students with new student holds in the system.";
	}

	if($viewform){
		$facultyid = $_SESSION['id'];
		$studentid = $_POST['studentid'];
		$studentfirstname = $_POST['studentfirstname'];
		$studentlastname = $_POST['studentlastname'];
		$submit_check_query = "SELECT courseid FROM newstudentadvisingform WHERE studentid=$studentid AND facultyid=$facultyid;";
		$submit_check_result = mysqli_query($connection,$submit_check_query);
		if($submit_check_result){
			echo "<h2>New Student Advising Form</h2>
			Student Name: ".$studentfirstname." ".$studentlastname."<br>
			Student ID: ".$studentid."<br>";
			$courses_entered_query = "SELECT newstudentadvisingform.courseid,courses.dept,courses.coursenum,courses.title,courses.credithours FROM newstudentadvisingform, courses WHERE newstudentadvisingform.studentid=$studentid AND newstudentadvisingform.facultyid=$facultyid AND courses.courseid = newstudentadvisingform.courseid;";
			$courses_entered_result = mysqli_query($connection,$courses_entered_query);
			echo "<h2>Courses Entered: <br></h2>";
			echo "<table>
			<tr>
			<th>Course ID</th>
			<th>Department</th>
			<th>Course Number</th>
			<th>Title</th>
			<th>Credit Hours</th>
			</tr>";

			while($row=mysqli_fetch_assoc($courses_entered_result)){
				echo "</tr>
				<td>".$row['courseid']."</td>
				<td>".$row['dept']."</td>
				<td>".$row['coursenum']."</td>
				<td>".$row['title']."</td>
				<td>".$row['credithours']."</td>
				</tr>";
			}
			echo "</table>

			<form method='post'>
			<input type='hidden' name='studentid' value='$studentid'>
			<input type='submit' value='Lift Hold' name='lift_hold'>
			</form>";
	
		}
		else{
			$form_not_submitted = "Student has not yet submitted new student advising form.";
		}
	}
	if($lifthold){

		$studentid = $_POST['studentid'];

		$lift_hold_query = "UPDATE advises SET hold = NULL WHERE studentid = $studentid;";
		$lift_hold_result = mysqli_query($connection,$lift_hold_query);

		$delete_form_query = "DELETE FROM newstudentadvisingform WHERE studentid = $studentid;";
		$delete_form_result = mysqli_query($connection,$delete_form_query);

		if($lift_hold_result && $delete_form_result){
			echo "Hold was lifted successfully! New student advising form has been deleted from system.";
		}
		else{
			echo "Hold was not able to be lifted.";
		}
	}







	echo "</body>";
	echo "</html>";
?>