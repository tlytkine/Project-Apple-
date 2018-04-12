<?php
	// Displays degree requirements

	// Login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role = 'STUDENT'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	} 
?>
<!DOCTYPE html>
<html>
<head>
	<title>View Degree Requirements</title>
<link rel ="stylesheet" type="text/css" href="style1.css"/>
</head>
<body>
	<h2>View Degree Requirements</h2>
	<!-- Queries for all degrees in the system and allows
		the user to select one of them-->
	<form method="post" action="degree_requirements.php">
		<select name="degree">
			<?php 
				$degrees_query = "SELECT degree_name FROM degrees;";
				$result = mysqli_query($conn, $degrees_query);
				while ($row = mysqli_fetch_assoc($result)) {
					$degree_name = $row['degree_name'];
					echo "<option value='$degree_name'>$degree_name
						</option>";
				}

			?>
		</select>
		<input type="hidden" name="list" value="true">
		<input type="submit" name="submit" value="List Degree Requirements">
	</form>
	<br>

	<?php 
		$list = $_POST['list'];
		// After a degree has been selected, query for the requirements
		// and display them
		if (strcmp($list, 'true') == 0) {
			echo "<table>
				<tr><th>CRN</th><th>Title</th></tr>";
			$degree = $_POST['degree'];
			$requirements_query = "SELECT crn, title 
			FROM degrees, courses 
			WHERE degree_name='$degree' AND courses.coursenum 
				IN (degrees.core1, degrees.core2, degrees.core3);";
			$result = mysqli_query($conn, $requirements_query);
			while ($row = mysqli_fetch_assoc($result)) {

				echo "<tr>";
				$crn = $row['crn'];
				$title = $row['title'];
				echo "<td>$crn</td><td>$title</td>";
				echo "</tr>";
			}

			echo "</table>";
		}

	?>

	<bt>
	<form method="post" action="form1.php">
		<input type="submit" name="submit" value="Back to Form1">
	</form>

</body>
</html>