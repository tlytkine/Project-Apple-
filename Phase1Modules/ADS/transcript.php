<?php
	// View a transcript
	
	// login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	}
?>
<html>
<head><title>Transcript</title></head>
<link rel ="stylesheet" type="text/css" href="style1.css"/>
<body>
<h1>Transcript</h1>
<?php
// get the gworld
$gwid = $_POST['gwid'];

// query for the name of the student
$name_query = "SELECT firstname, lastname 
FROM students 
WHERE gwid = '$gwid';";


$name_query_result = mysqli_query($conn, $name_query);

$row = mysqli_fetch_assoc($name_query_result);

//display
echo "Name: ".$row["firstname"]." ".$row["lastname"]."\n";
echo "<br>";
echo "GWID: ".$gwid."\n";

$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");
//Check	connection
if (!$conn)	{	
	die("Connection failed: " . mysqli_connect_error());	
}		

// query for all the taken courses
$courses_query = "SELECT course_status.coursenum, crn, title, grade, credithours
FROM course_status, courses
WHERE course_status.gwid = '$gwid'
AND course_status.coursenum = courses.coursenum;";


$courses_query_result = mysqli_query($conn, $courses_query);

if(mysqli_num_rows($courses_query_result)>0){
	echo"<table>
	<tr>
	<th>CRN"."&nbsp;&nbsp;"."</th>
	<th>Title"."&nbsp;&nbsp;"."</th>
	<th>Grade"."&nbsp;&nbsp;"."</th>
	</tr>";

	// display all taken courses
	while($row = mysqli_fetch_assoc($courses_query_result))
	{
		echo"<tr>
		<td>".$row["crn"]."</td>
		<td>".$row["title"]."</td>
		<td>".$row["grade"]."</td>
		</tr>";
	}
	echo"</table>";
}

// calculate the gpa of a student
$gpa_calc = "SELECT (Sum(qualitypoints*credithours)/Sum(credithours)) AS GPA 
FROM grade_calc, courses, course_status
WHERE grade_calc.grade = course_status.grade 
AND course_status.gwid = '$gwid'
AND course_status.coursenum = courses.coursenum;";

$gpa_calc_result = mysqli_query($conn, $gpa_calc);

// display 
if(mysqli_num_rows($gpa_calc_result)>0){
	while($row=mysqli_fetch_assoc($gpa_calc_result)){
		echo "GPA: ".$row["GPA"]."<br>";
	}
}



mysqli_close($conn);
?>


<b><a href="logout.php">Log Out</a></b>
<button onclick="history.go(-1);">Back </button>


</body>
</html>
