<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>

<html>
<head><title>Update Degree Submit</title></head>
<link rel="stylesheet" href="style.css">
<body>
	<?php

	if (strcmp($_POST['action'], 'input_degree') == 0) {
		$degree_html = "<select name ='major'>";
		$degree_html .= "<option value = '0'>-----</option>";
		$query = "SELECT DISTINCT degreename FROM degreerequirements;";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$degree = $row['degree_name'];
			$degree_html .= "<option value='$degree'>
			$degree</option>";
		}
		$degree_html .= "</select><br><br>";

		echo "<h2>Enter Degree Information.</h2><br>";
		echo "<form method='post' action='user.php'>
		<p>Degree Name<p><input type='text' name='degree_name'>
		<p>Core Course 1<p><input type='text' name='core1'>
		<p>Core Course 2<p><input type='text' name='core2'>
		<p>Core Course 3<p><input type='text' name='core3'>
		<input type='hidden' name='action' value='add_degree'>
		<input type='submit' name='submit' value='Add Degree'>
		</form>";

	}
	//add degree
	if (strcmp($_POST['action'], 'add_degree') == 0) {
		$degree_name = $_POST['degree_name'];
		$core1 = $_POST['core1'];
		$core2 = $_POST['core2'];
		$core3 = $_POST['core3'];


		$degree_query .= "INSERT INTO degreerequirements 
		(degree_name, courseid)
		VALUES ('$degree_name', '$core1'),('$degree_name', '$core2'),('$degree_name','$core3');";

		$degree_result = mysqli_query($conn, $degree_query);
		if (!$degree_result) {
			echo "<h2>Problem adding degree.</h2><br>";
			echo $degree_query . "<br>";
		} else {
			echo "<p>Insert Successfully</p>";
		}
		echo "<a href='admin.php'>Back</a>";
	}

	//Edit Degree Requirements Code
	if (strcmp($_POST['action'], 'edit_degree_requirements') == 0) {
		$degree_html = "<select name ='major'>";
		$degree_html .= "<option value = '0'>-----</option>";
		$query = "SELECT degree_name FROM degreerequirements;";
		$result = mysqli_query($conn, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$degree = $row['degree_name'];
			$degree_html .= "<option value='$degree'>
			$degree</option>";
		}
		$degree_html .= "</select><br><br>";

		echo "<h2>Edit Degree Requirements.</h2><br>";
		echo "<form method='post' action='user.php'>
		<p>Degree Name<p><input type='text' name='degree_name'>
		<p>Core Course 1<p><input type='text' name='core1'>
		<p>Core Course 2<p><input type='text' name='core2'>
		<p>Core Course 3<p><input type='text' name='core3'>
		<input type='hidden' name='action' value='edit_degree'>
		<input type='submit' name='submit' value='Edit Degree'>
		</form>";

	}
	// edit degree
	if (strcmp($_POST['action'], 'edit_degree') == 0) {
		$degree_name = $_POST['degree_name'];
		$courseid = $_POST['courseid'];



		$degree_query .= "UPDATE degreerequirements (degree_name, courseid)
		VALUES ('$degree_name', '$courseid');";

		$degree_result = mysqli_query($conn, $degree_query);
		if (!$degree_result) {
			echo "<h2>Problem editing requirement.</h2><br>";
			echo $degree_query . "<br>";
		} else {
			echo "<p>Updated Successfully</p>";
		}
		echo "<a href='admin.php'>Back</a>";
	}

	
	?>
</body>
</html>
