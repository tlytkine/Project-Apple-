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
echo "<td><form method='post' action='update_degree_submit.php'>
	<input type='hidden' name='action' value='edit_degree_requirements'>
	<input type='submit' value='Edit Degree Requirements'>
	</form></td></tr></table>";
?>


</body>
</html>
