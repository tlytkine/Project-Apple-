<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>

<html>
<head><title>Update Degrees</title></head>
<link rel="stylesheet" href="style.css">
<body>


<?php



	

	// edit degree
	if (strcmp($_POST['action'], 'update_degree') == 0) {

		if(isset($_POST['degreename'])&&
			(isset($_POST['currentcourseid'])&&$_POST['newcourseid'])){

			$degreename = $_POST['degreename'];
			$currentcourseid = $_POST['currentcourseid'];
			$newcourseid = $_POST['newcourseid'];

			$update_degree_query .= "UPDATE degreerequirements SET courseid = $newcourseid WHERE degreename = '$degreename' AND courseid = $currentcourseid;";

			$degree_result = mysqli_query($connection, $update_degree_query);
			if ($degree_result) {
				$update_degree_success = "Course requirements updated successfully!";
			} 
			else {
				$update_degree_error = "Error updating degree requirements.";
			}
		}
		else {
			$update_degree_invalid_input = "Please enter the degree name and current course id for the requirement you wish to update as they are shown in the table. In addition, make sure that the new course id you are entering is valid.";

		}
	}


	// Update degree name 
	if (strcmp($_POST['action'], 'update_degree_name') == 0) {

		if(isset($_POST['currentdegreename']) && isset($_POST['newdegreename'])){
			$currentdegreename = $_POST['currentdegreename'];
			$newdegreename = $_POST['newdegreename'];

			$update_degree_name_query = "UPDATE degreerequirements SET degreename = '$newdegreename' WHERE degreename = '$currentdegreename';";
			$update_degree_name_result = mysqli_query($connection, $update_degree_name_query);
			if($update_degree_name_result){
				$update_degree_name_success = "Degree name updated successfully!";
			}
			else {
				$update_degree_name_error = "Error updating degree name.";
			}
		}
		else {
			$update_degree_name_invalid_input = "Please make sure to enter the current name of the degree you wish to update and the name you wish to update it to.";
		}
	}


	// Add new course 
	if (strcmp($_POST['action'], 'add_new_course') == 0) {
		if(isset($_POST['degreename']) && isset($_POST['courseid'])){
			$degreename = $_POST['degreename'];
			$courseid = $_POST['courseid'];

			$add_new_course_query = "INSERT INTO degreerequirements(degreename,courseid)
			VALUES('$degreename',$courseid);";
			$add_new_course_result = mysqli_query($connection, $add_new_course_query);
			if($add_new_course_result){
				$add_new_course_success = "Course added successfully!";
			}
			else {
				$add_new_course_error = "Error adding course.";
			}
		}
		else {
			$add_new_course_invalid_input = "Make sure you are entering a valid degree name and courseid.";
		}


	}
	// delete course
	if (strcmp($_POST['action'], 'delete') == 0) {
		
		if(isset($_POST['degreename'])&&(isset($_POST['courseid']))){
			$degreename = $_POST['degreename'];
			$courseid = $_POST['courseid'];

			$delete_query = "DELETE FROM degreerequirements WHERE degreename='$degreename' AND courseid = $courseid;";
			$delete_result = mysqli_query($connection, $delete_query);

			if($delete_result){
				$course_delete_success = "Course deleted successfully!";
			}
			else {
				$course_delete_failure = "Course was not able to be deleted.";
			}
		}
		else {
			$course_delete_error = "Error deleting course.";
		}

	}
	//add degree
	if (strcmp($_POST['action'], 'add_degree') == 0) {
		$degreename = $_POST['degreename'];
		$core1 = $_POST['core1'];
		$core2 = $_POST['core2'];
		$core3 = $_POST['core3'];

		if(isset($_POST['degreename'])&&isset($_POST['core1'])&&isset($_POST['core2'])&&isset($_POST['core3'])){

			$degreename = $_POST['degreename'];
			$core1 = $_POST['core1'];
			$core2 = $_POST['core2'];
			$core3 = $_POST['core3'];
			$add_degree_query = "INSERT INTO degreerequirements(degreename, courseid) VALUES ('$degreename', $core1),('$degreename', $core2),('$degreename',$core3);";
			$degree_result = mysqli_query($connection, $add_degree_query);
			if ($degree_result) {
				$add_degree_success = "Degree added successfully!";

			} 
			else {
				$add_degree_failure = "This degree and its core courses already exist in the system. <br> To add or delete new courses for an existing degree, use the add degree and add course buttons respectively.";
			}
		}
		else {
			$add_degree_error = "You must fill all the fields. A unique not already existing degree name must be entered with the three core courses.";
		}
	}

	echo "<br><h2>Update Degrees Menu</h2>";

	$degree_query = "SELECT degreerequirements.degreename, degreerequirements.courseid, courses.dept, courses.coursenum, courses.title FROM degreerequirements, courses WHERE degreerequirements.courseid = courses.courseid;";
	$degree_result = mysqli_query($connection, $degree_query);

		echo "<table>
		<tr>
		<th>Degree Name</th>
		<th>Core Course ID</th>
		<th>Department</th>
		<th>Course Number</th>
		<th>Title</th>
		<th>Remove Course</th>
		</tr>";

		while($row = mysqli_fetch_assoc($degree_result)){
			echo "<tr>
			<td>".$row['degreename']."</td>
			<td>&nbsp;&nbsp;".$row['courseid']."</td>
			<td>&nbsp;&nbsp;".$row['dept']."</td>
			<td>&nbsp;&nbsp;".$row['coursenum']."</td>
			<td>&nbsp;&nbsp;".$row['title']."</td>
			<td><form method='post'>
			<input type='hidden' name='action' value='delete'>
			<input type='hidden' name='degreename' value='".$row['degreename']."'>
			<input type='hidden' name='courseid' value='".$row['courseid']."'>
			<input type='submit' value='Remove'>
			</form></td> 
			</tr>";
		}
		echo "</table>";
	



echo "
<br>
<table>
<tr>
<td>
<form method='post'>
<input type='hidden' name='action' value='input_degree'>
<input type='submit' value='Add Degree'>
</form>
</td>
&nbsp;&nbsp;
<td>
<form method='post'>
<input type='hidden' name='action' value='add_course'>
<input type='submit' value='Add Course'>
</form>
</td>
&nbsp;&nbsp;
<td>
<form method='post'>
<input type='hidden' name='action' value='edit_degree_name'>
<input type='submit' value='Update Degree Name'>
</form>
</td>
&nbsp;&nbsp;
<td>
<form method='post'>
<input type='hidden' name='action' value='update_degree_requirements'>
<input type='submit' value='Update Core Course'>
</form>
</td>
</tr>
</table>";

	if(strcmp($_POST['action'], 'edit_degree_name')== 0){
		$degree_query = "SELECT degreerequirements.degreename, degreerequirements.courseid, courses.dept, courses.coursenum, courses.title FROM degreerequirements, courses WHERE degreerequirements.courseid = courses.courseid;";
		$degree_result = mysqli_query($connection, $degree_query);
		echo "<h2>Update Degree Name</h2>";
		echo "<form method='post'>
		<p>Select Degree</p><select name='currentdegreename'>";
		while($row=mysqli_fetch_assoc($degreeresult)){
			echo "option value='".$row['degreename']."'>";
			echo $row['degreename'];
			echo "</option>";
		}
		echo "</select>";
		echo "<p>Enter New Name</p><input type='text' name='newdegreename'>
		<input type='hidden' name='action' value='update_degree_name'>
		<br>
		<input type='submit' name='submit' value='Update'>
		</form";
	}
	if (strcmp($_POST['action'], 'input_degree') == 0) {

		echo "<h2>Enter Degree Information.</h2><br>";
		echo "<form method='post'>
		<p>A degree must have three core courses.</p> <br>
		<p>Degree Name<p><input type='text' name='degreename'>";
		$courses_query = "SELECT courseid, dept, coursenum, title FROM courses;";
		$courses_result1 = mysqli_query($connection,$courses_query);
		echo "<p>Core Course 1</p><select name='core1'>";
		while($row1=mysqli_fetch_assoc($courses_result1)){
			echo "<option value='".$row1['courseid']."'>";
			echo $row1['dept']." ".$row1['coursenum']."-".$row1['title'];
			echo "</option>";
		}
		echo "</select>";
		$courses_query = "SELECT courseid, dept, coursenum, title FROM courses;";
		$courses_result2 = mysqli_query($connection,$courses_query);
		echo "<p>Core Course 2</p><select name='core2'>";
		while($row2=mysqli_fetch_assoc($courses_result2)){
			echo "<option value='".$row2['courseid']."'>";
			echo $row2['dept']." ".$row2['coursenum']. "-".$row2['title'];
			echo "</option>";
		}
		echo "</select>";
		$courses_query = "SELECT courseid, dept, coursenum, title FROM courses;";
		$courses_result3 = mysqli_query($connection,$courses_query);
		echo "<p>Core Course 3</p><select name='core3'>";
		while($row3=mysqli_fetch_assoc($courses_result3)){
			echo "<option value='".$row3['courseid']."'>";
			echo $row3['dept']." ".$row3['coursenum']. "-".$row3['title'];
			echo "</option>";
		}
		echo "</select>
		<input type='hidden' name='action' value='add_degree'>
		<br>
		<input type='submit' name='submit' value='Add Degree'>
		</form>";

	}
	if(strcmp($_POST['action'], 'add_course')== 0){
		$degree_query = "SELECT degreerequirements.degreename,degreerequirements.courseid,courses.dept,courses.coursenum,courses.title FROM degreerequirements,courses WHERE degreerequirements.courseid = courses.courseid;";
		$degree_result = mysqli_query($connection,$degree_query);
		echo "<h2>Add Course</h2>";
		echo "<form method='post'>
		<p>Degree Name</p>
		<select name='degreename'>";
		while($row=mysqli_fetch_assoc($degree_result)){
			echo "option value='".$row['degreename']."'>";
			echo $row['degreename']." ";
			echo "</option>";
		}
		echo "</select>";
		$courses_query = "SELECT courseid, dept, coursenum, title FROM courses;";
		$courses_result = mysqli_query($connection,$courses_query);
		echo "<p>Course</p><select name='courseid>";
		while($row=mysqli_fetch_assoc($courses_result)){
			echo "<option value='".$row['courseid']."'>";
			echo $row['dept']." ".$row['coursenum']."-".$row['title'];
			echo "</option>";
		}
		echo "</select>
		<input type='hidden' name='action' value='add_new_course'><br>
		<input type='submit' name='submit' value='Add Course'>
		</form>";
	}

	//Update Degree Requirements Code
	if (strcmp($_POST['action'], 'update_degree_requirements') == 0) {
		$degree_result = mysqli_query($connection, $degree_query);
		echo "<h2>Update Core Course</h2>";
		echo "<form method='post'>
		<p>Degree Name<p><select name='degreename'>";
		while($row=mysqli_fetch_assoc($degreeresult)){
			echo "option value='".$row['degreename']."'>";
			echo $row['degreename'];
			echo "</option>";
		}
		echo "</select>";
		$degree_result = mysqli_query($connection, $degree_query);
		echo "<p>Current Course<p><select name='currentcourseid'>";
		while($row=mysqli_fetch_assoc($degreeresult)){
			echo "option value='".$row['courseid']."'>";
			echo $row['dept']." ".$row['coursenum']."-".$row['title'];
			echo "</option>";
		}
		echo "</select>";
		$courses_query = "SELECT courseid, dept, coursenum, title FROM courses;";
		$courses_result = mysqli_query($connection,$courses_query);
		echo "<p>New Course</p><select name='newcourseid>";
		while($row=mysqli_fetch_assoc($courses_result)){
			echo "<option value='".$row['courseid']."'>";
			echo $row['dept']." ".$row['coursenum']."-".$row['title'];
			echo "</option>";
		}
		echo "</select>";
		echo "<input type='hidden' name='action' value='update_degree'><br>
		<input type='submit' name='submit' value='Update'>
		</form>";

	}
	// Update degree requirements notifications 
	if($update_degree_success){
		echo "<br>" . $update_degree_success . "<br>";
	}
	else if($update_degree_error){
		echo "<br>" . $update_degree_error . "<br>";
	}
	else if($update_degree_invalid_input){
		echo "<br>" . $update_degree_invalid_input . "<br>";
	}
	// Add new course notifications 
	else if($add_new_course_success){
		echo "<br>" . $add_new_course_success . "<br>";
	}
	else if($add_new_course_error){
		echo "<br>" . $add_new_course_degree_error . "<br>";
	}
	else if($add_new_course_invalid_input){
		echo "<br>" . $add_new_course_invalid_input . "<br>";
	}
	// Update degree name notifications 
	else if($update_degree_name_success){
		echo "<br>" . $update_degree_name_success . "<br>";
	}
	else if($update_degree_name_error){
		echo "<br>" . $update_degree_name_error . "<br>";
	}
	else if($update_degree_name_invalid_input){
		echo "<br>" . $update_degree_name_invalid_input . "<br>";
	}
	// Delete course notifications 
	else if($course_delete_success){
		echo "<br>" . $course_delete_success . "<br>";
	}
	else if($course_delete_failure){
		echo "<br>" . $course_delete_failure . "<br>";
	}
	else if($course_delete_error){
		echo "<br>" . $course_delete_error . "<br>";
	}
	// Add degree notifications 
	else if($add_degree_success){
		echo "<br>" . $add_degree_success . "<br>";
	}
	else if($add_degree_failure){
		echo "<br>" . $add_degree_failure . "<br>";
	}
	else if($add_degree_error){
		echo "<br>" . $add_degree_error . "<br>";
	}






	

	

	

echo "</body>";
echo "</html>";
?>