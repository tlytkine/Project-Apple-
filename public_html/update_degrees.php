<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSON["id"];
?>

<html>
<head><title>Update Degrees</title></head>
<body>
<b>Update Degrees</b><br>

<?php

echo "<br><h2>Degrees</h2>";
$degree_query = "SELECT degree_name, courseid
FROM degreerequirements;";

$degree_result = mysqli_query($conn, $degree_query);

echo "<table>
<tr>
<th>Degree Name</th>
<th>Core Courses</th>
</tr>";

while($row = mysqli_fetch_assoc($degree_result)){
	echo "<tr>
	<td>".$row['degree_name']."</td>
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
