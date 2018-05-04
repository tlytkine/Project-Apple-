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

echo "<br><h2>Degrees</h2>";
$degree_query = "SELECT degreename, courseid
FROM degreerequirements;";

$degree_result = mysqli_query($connection, $degree_query);

echo "<table>
<tr>
<th>Degree Name</th>
<th>Core Courses</th>
<th>Remove Course</th>
</tr>";

while($row = mysqli_fetch_assoc($degree_result)){
	echo "<tr>
	<td>".$row['degreename']."</td>
	<td>&nbsp;&nbsp;".$row['courseid']."</td>
	<td><form method='post'>
	<input type='hidden' name='action' value='delete'>
	<input type='hidden' name='degreename' value='".$row['degreename']."'>
	<input type='hidden' name='courseid' value='".$row['courseid']."'>
	<input type='submit' value='Remove'>
	</form></td> 
	</tr>";
}
echo "</table>";

echo "<table>
	<tr><td>";
echo "<form method='post'>
	<input type='hidden' name='action' value='input_degree'>
	<input type='submit' value='Add Degree'>
	</form></td>";
echo "<td><form method='post'>
	<input type='hidden' name='action' value='update_degree_requirements'>
	<input type='submit' value='Edit Degree Requirements'>
	</form></td></tr></table>";


	if (strcmp($_POST['action'], 'input_degree') == 0) {

		echo "<h2>Enter Degree Information.</h2><br>";
		echo "<form method='post'>
		<p>A degree must have three core courses.</p> <br>
		<p>Degree Name<p><input type='text' name='degreename'>
		<p>Core Course 1<p><input type='text' name='core1'>
		<p>Core Course 2<p><input type='text' name='core2'>
		<p>Core Course 3<p><input type='text' name='core3'>
		<input type='hidden' name='action' value='add_degree'>
		<br>
		<input type='submit' name='submit' value='Add Degree'>
		</form>";

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
			$degree_query = "INSERT INTO degreerequirements(degreename, courseid) VALUES ('$degreename', $core1),('$degreename', $core2),('$degreename',$core3);";
			$degree_result = mysqli_query($connection, $degree_query);
			if ($degree_result) {
				echo "Degree added successfully!";
			} 
			else {
				echo "This degree and its core courses already exist in the system. To edit a degree or to add a new course, use the edit degree requirements button and add course buttons respectively.";
			}
		}
		else {
			echo "You must fill all the fields. A unique non-existing degree name must be entered with three core courses.";
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
				echo "Course deleted successfully!";
			}
			else {
				echo "Course was not able to be deleted.";
			}
		}
		else {
			echo "Error deleting course.";
		}

	}


	//Edit Degree Requirements Code
	if (strcmp($_POST['action'], 'update_degree_requirements') == 0) {

		echo "<h2>Update Degree Requirements.</h2><br>";
		echo "<p>Please enter the degree name , current courseid that you wish to have updated and the courseid of the course you wish to have the requirement updated to.</p>";
		echo "<form method='post'>
		<p>Degree Name<p><input type='text' name='degreename'>
		<p>Current Course ID<p><input type='text' name='currentcourseid'>
		<p>New Course ID<p><input type='text' name='newcourseid'>
		<input type='hidden' name='action' value='update_degree'>
		<input type='submit' name='submit' value='Update'>
		</form>";

	}
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
				echo "Course requirements updated successfully!";
			} 
			else {
				echo "Error updating degree requirements.";
			}
		}
		else {
			echo "Please enter the degree name and current course id for the requirement you wish to update as they are shown in the table. In addition, make sure that the new course id you are entering is valid.";

		}
	}
	

echo "</body>";
echo "</html>";
?>