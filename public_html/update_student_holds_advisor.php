<?php 

$allowed_user_types = array(
        "ADVISOR"
 );
include 'header.php';
include 'db-connect.php';
$facultyid = $_SESSION["id"];
?>

<html>
<head><title>Update Student Holds</title></head>
<link rel="stylesheet" href="style.css">
<body>

<?php


	echo "<h1>Update Student Holds</h1>";
	$studentid = $_POST['studentid'];


	# Lift Hold Button 
	if (isset($_POST['lift'])) {
		$lift_query = "UPDATE advises
		SET hold=NULL
		WHERE studentid = '$studentid' AND 
		facultyid = '$facultyid'";
		$lift_result = mysqli_query($connection, $lift_query);
		if(mysqli_affected_rows($connection)>0){
			$holdlifted = "Hold was successfully lifted!";
		}
	}



	# Place Hold Button 
	if (isset($_POST['place'])) {
		if(isset($_POST['holdtext'])){
			$holdtext = $_POST['holdtext'];
			if(strlen($holdtext)>0){
				$place_query = "UPDATE advises
				SET hold='$holdtext'
				WHERE studentid = '$studentid' AND 
				facultyid = '$facultyid'";

				$place_result = mysqli_query($connection, $place_query);
				if(mysqli_affected_rows($connection)>0){
					$holdplaced = "Hold was successfully placed!<br>";
				}
				else {
					$holdinvalid = "Please try a different hold.<br>";
				}
			}
			else {
				$holdblank = "Hold was not placed. Please enter a hold in the text field.<br>";
			}
		}
	}

	// Get all students / faculty advisors 
	$advisee_query = "SELECT P1.firstname AS studentfirstname, P1.lastname AS studentlastname, P2.firstname AS facultyfirstname, P2.lastname AS facultylastname, advises.studentid, advises.facultyid, advises.hold, advises.degreename FROM personalinfo AS P1, personalinfo AS P2, advises WHERE P1.id = advises.studentid AND P2.id = advises.facultyid AND advises.facultyid=$facultyid;"; 
	$advisee_result = mysqli_query($connection, $advisee_query);

	$row = mysqli_fetch_assoc($advisee_result);
	if(ISSET($row['studentid'])){

		$advisee_query = "SELECT P1.firstname AS studentfirstname, P1.lastname AS studentlastname, P2.firstname AS facultyfirstname, P2.lastname AS facultylastname, advises.studentid, advises.facultyid, advises.hold, advises.degreename FROM personalinfo AS P1, personalinfo AS P2, advises WHERE P1.id = advises.studentid AND P2.id = advises.facultyid AND advises.facultyid=$facultyid;"; 
		$advisee_result = mysqli_query($connection, $advisee_query);



		echo "<table>
		<tr><th>Advisee</th>
		<th>Student ID</th>
		<th>Faculty Advisor</th>
		<th>Faculty ID</th>
		<th>Hold</th>
		<th>Lift Hold</th>
		<th>Place Hold</th>
		</tr>";

		// Displays a table of all students along with actions that can be taken
		while ($row = mysqli_fetch_assoc($advisee_result)){
			echo "<tr><td>".$row['studentfirstname']." ".$row['studentlastname']."</td>
				<td>".$row['studentid']."</td>
				<td>".$row['facultyfirstname']." ".$row['facultylastname']."</td>
				<td>".$row['facultyid']."</td>
				<td>";
				if(ISSET($row['hold'])){
					echo $row['hold'];
				}
				else{ 
					echo "None";
				}
				echo "</td>
				<form method ='post'>
				<input type='hidden' name='studentid' value ='".$row['studentid']."'>
				<input type='hidden' name='facultyid' value='".$row['facultyid']."'>
				<td><input type='submit' name='lift' value='Lift Hold'></form></td>
				<form method='post'>
				<input type='hidden' name='studentid' value ='".$row['studentid']."'>
				<input type='hidden' name='facultyid' value='".$row['facultyid']."'>
				<td><input type='text' name='holdtext'>
				<input type='submit' name='place' value='Place Hold'></td>
				</form>
				</tr>";
			}
			echo "</table>";


			if($holdlifted){
				echo "<br>";
				echo $holdlifted;
				echo "<br>";
			}
			else if($holdplaced){
				echo "<br>";
				echo $holdplaced;
				echo "<br>";
			}
			else if($holdinvalid){
				echo "<br>";
				$holdinvalid;
				echo "<br>";
			}
			else if($holdblank){
				echo "<br>";
				echo $holdblank;
				echo "<br>";
			}
	}
	else {
		echo "You are not currently assigned as an advisor to any student.";
	}

?>


</body>
</html>