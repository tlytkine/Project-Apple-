<?php 
	include 'header.php';
	include 'db-connect.php';
	$id = $_SESSION["id"];
	$query = "SELECT firstname, lastname
	FROM personalinfo WHERE id='$id';";
	$result = mysqli_query($connection, $query);
	$row = mysqli_fetch_assoc($result);
	$firstname = $row['firstname'];
	$lastname = $row['lastname'];


	$fid_query = "SELECT facultyid FROM advises WHERE studentid = '$id';";
	$fid_result = mysqli_query($connection, $fid_query);
	$row = mysqli_fetch_assoc($fid_result);
	$fid = $row['facultyid'];
	$fa_query = "SELECT firstname, lastname FROM personalinfo 
	WHERE id = '$fid';";
	$fa = mysqli_query($connection, $fa_query);
	$row = mysqli_fetch_assoc($fa);
	$advisor_first = $row['firstname'];
	$advisor_last = $row['lastname'];

?>

<html>
<head><title>Form 1</title></head>
<link rel="stylesheet" href="style.css">
<body>

<!-- Display some information that will get passed with the submitted form-->
<h1>Form 1</h1>
<form method="post" action="ads.php">
<p>First Name:</p>
<input type="text" name="first_name" value=
<?php echo '"' . $firstname . '"';	?> readonly>
<p>Last Name:</p>
<input type="text" name="last_name" value=
<?php echo '"' . $lastname . '"'; ?> readonly>
<p>Degree: </p>
<select name="degreename">
<!-- Inser other degrees -->
<option value="MS_CS">MS_CS</option>
</select>
<p>GW ID:</p>
<input type="text" name="studentid" value=
<?php echo '"' . $id . '"';	?> readonly>


<p>Faculty Advisor:</p>
<?php 
echo $advisor_first;
echo " ";
echo $advisor_last;
?> 



<h3>Courses Taken<br></h3>
<!-- Class Input -->
<p>Will not recognize any duplicate classes</p><br>
<?php 
	for ($i = 1; $i <= 12; $i++) {
		// Displays an drop down box with all available classes
		echo "<select name ='course$i'>";
		echo "<option value = '0'>-----</option>";
		$query = "SELECT dept,coursenum, courseid FROM courses;";
		$result = mysqli_query($connection, $query);
		while ($row = mysqli_fetch_assoc($result)) {
			$coursenum = $row['coursenum'];
			$courseid = $row['courseid'];
			$dept = $row['dept'];
			echo "<option value='$courseid'>".$dept." ".$coursenum."</option>";
		}
		echo "</select>";
		echo "<br><br>";
	}

?>
<br>
<!-- Submits form 1-->
<input type="submit" value="Apply To Graduate">
&nbsp;&nbsp;



</body>
</html>
