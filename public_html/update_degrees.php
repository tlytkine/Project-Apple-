<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>

<html>
<head><title>Update Degrees</title></head>
<link rel="stylesheet" href="style.css">
<body>
<b>Update Degrees</b><br>

<?php

echo "<br><h2>Degrees</h2>";
$degree_query = "SELECT degreename, courseid
FROM degreerequirements;";

$degree_result = mysqli_query($connection, $degree_query);

echo "<table>
<tr>
<th>Degree Name</th>
<th>Core Courses</th>
</tr>";

while($row = mysqli_fetch_assoc($degree_result)){
	echo "<tr>
	<td>".$row['degreename']."</td>
	<td>&nbsp;&nbsp;".$row['courseid']."</td>
	</tr>";
}
echo "</table>";

echo "<table>
	<tr><td>";
echo "<form method='post' action='update_degree_submit.php'>
	<input type='hidden' name='action' value='input_degree'>
	<input type='submit' value='Add Degree'>
	</form>";
echo "<td><form method='post'>
	<input type='hidden' name='action' value='edit_degree_requirements'>
	<input type='submit' value='Edit Degree Requirements'>
	</form></td></tr></table>";


	if (strcmp($_POST['action'], 'input_degree') == 0) {
		$degree_html = "<select name ='major'>";
		$degree_html .= "<option value = '0'>-----</option>";
		$query = "SELECT DISTINCT degreename FROM degreerequirements;";
		$result = mysqli_query($connection, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$degree = $row['degreename'];
			$degree_html .= "<option value='$degree'>
			$degree</option>";
		}
		$degree_html .= "</select><br><br>";

		echo "<h2>Enter Degree Information.</h2><br>";
		echo "<form method='post'>
		<p>Degree Name<p><input type='text' name='degreename'>
		<p>Core Course 1<p><input type='text' name='core1'>
		<p>Core Course 2<p><input type='text' name='core2'>
		<p>Core Course 3<p><input type='text' name='core3'>
		<input type='hidden' name='action' value='add_degree'>
		<input type='submit' name='submit' value='Add Degree'>
		</form>";

	}
	//add degree
	if (strcmp($_POST['action'], 'add_degree') == 0) {
		$degreename = $_POST['degreename'];
		$core1 = $_POST['core1'];
		$core2 = $_POST['core2'];
		$core3 = $_POST['core3'];


		$degree_query .= "INSERT INTO degreerequirements 
		(degreename, courseid)
		VALUES ('$degreename', '$core1'),('$degreename', '$core2'),('$degreename','$core3');";

		$degree_result = mysqli_query($connection, $degree_query);
		if (!$degree_result) {
			echo "<h2>Problem adding degree.</h2><br>";
			echo $degree_query . "<br>";
		} else {
			echo "<p>Insert Successfully</p>";
		}
	}

	//Edit Degree Requirements Code
	if (strcmp($_POST['action'], 'edit_degree_requirements') == 0) {
		$degree_html = "<select name ='major'>";
		$degree_html .= "<option value = '0'>-----</option>";
		$query = "SELECT degreename FROM degreerequirements;";
		$result = mysqli_query($connection, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$degree = $row['degreename'];
			$degree_html .= "<option value='$degree'>
			$degree</option>";
		}
		$degree_html .= "</select><br><br>";

		echo "<h2>Edit Degree Requirements.</h2><br>";
		echo "<form method='post' action='user.php'>
		<p>Degree Name<p><input type='text' name='degreename'>
		<p>Core Course 1<p><input type='text' name='core1'>
		<p>Core Course 2<p><input type='text' name='core2'>
		<p>Core Course 3<p><input type='text' name='core3'>
		<input type='hidden' name='action' value='edit_degree'>
		<input type='submit' name='submit' value='Edit Degree'>
		</form>";

	}
	// edit degree
	if (strcmp($_POST['action'], 'edit_degree') == 0) {
		$degreename = $_POST['degreename'];
		$courseid = $_POST['courseid'];



		$degree_query .= "UPDATE degreerequirements (degreename, courseid)
		VALUES ('$degreename', '$courseid');";

		$degree_result = mysqli_query($connection, $degree_query);
		if (!$degree_result) {
			echo "<h2>Problem editing requirement.</h2><br>";
			echo $degree_query . "<br>";
		} else {
			echo "<p>Updated Successfully</p>";
		}
	}
	?>


</body>
</html>
