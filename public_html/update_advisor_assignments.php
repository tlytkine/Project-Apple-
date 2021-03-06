<?php 

	$allowed_user_types = array(
        "GS",
        "ADMIN"
 	);

	include 'header.php';
	include 'db-connect.php';
?>

<html>
<head><title>Update Advisor Assignments</title></head>
<link rel="stylesheet" href="style.css">
<body>
<h1>View / Edit Faculty Advisor</h1>
<?php 
	$id = $_SESSION["id"];

	if(ISSET($_POST['assign1'])){
		$assign1 = $_POST['assign1'];
	}
	if(ISSET($_POST['assign2'])){
		$assign2 = $_POST['assign2'];
	}

	if($assign1){
		$facultyidother = $_POST['facultyidother'];
		$studentidother = $_POST['studentidother'];
		$advisor_update = "UPDATE advises SET facultyid = $facultyidother WHERE 
		studentid = $studentidother;";
		$advisor_result = mysqli_query($connection, $advisor_update);

		if(mysqli_affected_rows($connection)>0){
			$assignsuccess1 = "Advisor successfully assigned!";
		}
		else if($advisor_result){
			$alreadyassigned1 = "Advisor is already assigned to student.";
		}

		$current_students_result = mysqli_query($connection, $current_students);
		$facultyresult = mysqli_query($connection,$facultyquery);
		$result = mysqli_query($connection, $query);

	}
	if($assign2){

		$facultyidnew = $_POST['facultyidnew'];
		$studentidnew = $_POST['studentidnew'];
		$advisor_update = "UPDATE advises SET facultyid = $facultyidnew WHERE 
		studentid = $studentidnew;";
		$advisor_result = mysqli_query($connection, $advisor_update);

		if(mysqli_affected_rows($connection)>0){
			$assignsuccess2 = "Advisor successfully assigned!";
		}
		else if($advisor_result) {
			$alreadyassigned2 = "Advisor is already assigned to student.";
		}
		else {

		}

		$current_students_result = mysqli_query($connection, $current_students);
		$facultyresult = mysqli_query($connection,$facultyquery);
		$result = mysqli_query($connection, $query);

		if($advisor_result){
			$assignsuccess2 = "Advisor successfully assigned!";
		}
	}


	$query = "SELECT P1.firstname AS studentfirstname, P1.lastname AS studentlastname, P1.id AS studentid, hold, degreename, P2.firstname AS advisorfirstname, P2.lastname AS advisorlastname, P2.id AS advisorid FROM personalinfo AS P1, personalinfo AS P2, roles AS R1, roles AS R2,  advises WHERE R1.role='STUDENT' AND R1.id = P1.id AND advises.studentid = P1.id AND P2.id = advises.facultyid AND R2.role = 'ADVISOR' AND R2.id = advises.facultyid;";

	$result = mysqli_query($connection, $query);



	echo "<table>
	<tr>
	<th>Name</th>
	<th>Student ID</th>
	<th>Hold</th>
	<th>Degree Name</th>
	<th>Faculty Advisor</th>
	<th>Faculty ID</th>
	<th>&nbsp;&nbsp;&nbsp;Assign</th>
	</tr>";

	while($row = mysqli_fetch_assoc($result)){
	
		echo "<tr>
		<td>".$row['studentfirstname']." ".$row['studentlastname']."</td>
		<td>".$row['studentid']."</td>
		<td>";
		if(ISSET($row['hold'])){
			echo $row['hold'];
		}
		else{ 
			echo "None";
		}
		echo "</td> 
		<td>".$row['degreename']."</td>
		<td>".$row['advisorfirstname']." ".$row['advisorlastname']."</td>
		<td>".$row['advisorid']."</td>";

		$facultyquery = "SELECT firstname AS facultyfirstname,lastname AS facultylastname,personalinfo.id AS facultyid FROM personalinfo,roles WHERE personalinfo.id = roles.id AND roles.role='ADVISOR';";
		$facultyresult = mysqli_query($connection,$facultyquery);
		echo "<td><form method = 'post'><select name ='facultyidother'>";
		while($row1 = mysqli_fetch_assoc($facultyresult)){
			echo "<option value ='".$row1['facultyid']."' name='facultyidother'>".$row1['facultyfirstname']." ".$row1['facultylastname']."</option>";
		}
		echo "</select> <input type='hidden' name='studentidother' value ='".$row['studentid']."'>
		<input type='submit' value='Assign' name='assign1'>";
		echo "</form></td>";
		echo "</tr>";
	}
	echo "</table>";


	echo "<br>";
	if($assignsuccess1){
		echo $assignsuccess1;
		echo "<br>";
	}
	else if($alreadyassigned1){
		echo $alreadyassigned1;
		echo "<br>";
	}

	echo "<br>";


	$new_student_check_query = "SELECT * FROM advises WHERE facultyid IS NULL;";
	$new_student_check_result = mysqli_query($connection,$new_student_check_query);

	$new_students = mysqli_fetch_assoc($new_student_check_result);

	echo "<h1>Assign New Faculty Advisor</h1>";
	if(ISSET($new_students['studentid'])){

		$current_students = "SELECT firstname,lastname,personalinfo.id,degreename,hold FROM personalinfo, advises, roles WHERE personalinfo.id = roles.id AND roles.role = 'STUDENT' AND advises.studentid = roles.id AND advises.facultyid IS NULL;";
		$current_students_result = mysqli_query($connection, $current_students);


			$facultyquery = "SELECT firstname AS facultyfirstname,lastname AS facultylastname,personalinfo.id AS facultyid FROM personalinfo,roles WHERE personalinfo.id = roles.id AND roles.role='ADVISOR';";
			$facultyresult = mysqli_query($connection,$facultyquery);


			echo "<table>
			<tr>
			<th>Name</th>
			<th>Student ID</th>
			<th>Hold</th>
			<th>Degree Name</th>
			<th>&nbsp;&nbsp;&nbsp;Assign</th>
			</tr>";



			while($row = mysqli_fetch_assoc($current_students_result)){
				$studentidnew = $row['id'];
				echo "<tr>
				<td>".$row['firstname']." ".$row['lastname']."</td>
				<td>".$row['id']."</td>
				<td>";
				if(ISSET($row['hold'])){
					echo $row['hold'];
				}
				else{ 
					echo "None";
				}
				echo "</td>
				<td>".$row['degreename']."</td>
				<td><form method='post'><select name ='facultyidnew'>";
				while($row1 = mysqli_fetch_assoc($facultyresult)){
					echo "<option value ='".$row1['facultyid']."' name='facultyidnew'>".$row1['facultyfirstname']." ".$row1['facultylastname']."</option>";
				}
				echo "</select><input type='hidden' name='studentidnew' value ='".$row['id']."'>
				<input type='submit' value='Assign' name='assign2'>";
				echo "</form></td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "<br>";
			if($assignsuccess2){
				echo $assignsuccess2;
				echo "<br>";
			}
			else if($alreadyassigned2){
				echo $alreadyassigned2;
				echo "<br>";
			}
	}
	else {
		echo "There are currently no students in the system that do not have faculty advisors assigned to them.";
	}

?>
</body>
</html>