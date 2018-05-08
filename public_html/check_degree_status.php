<?php 
$allowed_user_types = array(
        "STUDENT"
 );
include 'header.php';
include 'db-connect.php';
$id = $_SESSION["id"];
?>

<html>
<head><title>Check Degree Status</title></head>
<link rel="stylesheet" href="style.css">
<body><h1>Check Degree Status</h1>


<?php
	$degreenamequery = "SELECT degreename FROM advises WHERE studentid=$id;";


	$degreenameresult = mysqli_query($connection, $degreenamequery);

	$row = mysqli_fetch_assoc($degreenameresult);
	$degreename = $row['degreename'];

	echo "Degree Name: ";
	echo $degreename;
	echo "<br>";

	$degreerequirementsquery = "SELECT degreerequirements.courseid,dept,coursenum,title FROM degreerequirements, courses WHERE degreerequirements.degreename = '$degreename' AND courses.courseid = degreerequirements.courseid;";
	$degreerequirementsresult = mysqli_query($connection, $degreerequirementsquery);

	echo "<h3>Core Course Status</h3>
	<table>
	<tr>
	<th>Course ID</th>
	<th>Department</th>
	<th>Course Num</th>
	<th>Title</th>
	<th>Requirement Satisfied?</th>
	</tr>";
	$num_core_courses = 0;
	while($row = mysqli_fetch_assoc($degreerequirementsresult)){
		echo "<tr>
		<td>".$row['courseid']."</td>
		<td>".$row['dept']."</td>
		<td>".$row['coursenum']."</td>
		<td>".$row['title']."</td>
		<td>";
		$cid = $row['courseid'];

		$requirement_check_query = "SELECT studentid,grade FROM transcripts,courses WHERE transcripts.studentid = $id AND courses.courseid = $cid AND transcripts.dept = courses.dept AND courses.coursenum = transcripts.coursenum;";
		$requirement_check_result = mysqli_query($connection, $requirement_check_query);
		$row = mysqli_fetch_assoc($requirement_check_result);
		if(ISSET($row['grade'])){
			$grade = $row['grade'];
			if(strcmp($grade, 'IP')==0){
				echo "In progress";
			}
			else {
				echo "&#10004";
				$num_core_courses++;
			}
		}
		else {
			echo "&#10008";
		}
		echo "</td>
		</tr>";
	}

	echo "</table>
	<br>
	<b>Graduation Requirements: </b>
	<table>
	<tr>
	<th>Requirement</th>
	<th>Status</th>
	<th>Satisfied?</th>
	</tr>
	<tr>
	<td>Core Courses</td>
	<td>";
	$numcorecoursesquery = "SELECT COUNT(courseid) AS numcorecourses FROM degreerequirements WHERE degreename='$degreename';";
	$numcorecoursesresult = mysqli_query($connection, $numcorecoursesquery);
	$row = mysqli_fetch_assoc($numcorecoursesresult);
	$numcorecourses = $row['numcorecourses'];
	echo "$numcorecourses"; 
	echo " core courses taken </td>
	<td>";
	if($numcorecourses==$num_core_courses){
		echo "&#10004";
	}
	else {
		echo "&#10008";
	}
	echo "</td>
	</tr>
	<tr>
	<td>Minimum of 10 courses</td>
	<td>";
	$number_of_courses_query = "SELECT COUNT(studentid) AS numcourses FROM transcripts WHERE studentid = $id;";
	$number_of_courses_result = mysqli_query($connection,$number_of_courses_query);
	$row = mysqli_fetch_assoc($number_of_courses_result);
	$numcourses = $row['numcourses'];
	echo $numcourses;
	echo " courses taken</td>
	<td>";
	if($numcourses >= 10){
		echo "&#10004";
	}
	else {
		echo "&#10008";
	}
	echo "</td>
	</tr>
	<td>Minimum of 30 credit hours</td>
	<td>";
	$credit_hour_query = "SELECT SUM(credithours) AS credithoursum FROM courses,transcripts WHERE transcripts.dept = courses.dept AND courses.coursenum = transcripts.coursenum AND transcripts.studentid = $id AND transcripts.grade <> 'IP';";
	$credit_hour_result = mysqli_query($connection,$credit_hour_query);
	$row = mysqli_fetch_assoc($credit_hour_result);
	$credithoursum = $row['credithoursum'];
	echo $credithoursum;
	echo " credit hours completed
	</td>
	<td>";
	if($credithoursum >= 30){
		echo "&#10004";
	}
	else{
		echo "&#10008";
	}
	echo "</td>
	</tr>
	<td>Minimum GPA of 3.0</td>
	<td>";
	$gpa_calc_query = "SELECT (Sum(qualitypoints*credithours)/Sum(credithours)) AS GPA, transcripts.year FROM gradecalc, courses, transcripts WHERE gradecalc.grade = transcripts.grade AND transcripts.studentid=$id AND transcripts.coursenum = courses.coursenum;";
	$gpa_calc_result = mysqli_query($connection, $gpa_calc_query);
	$row = mysqli_fetch_assoc($gpa_calc_result);
	$gpa = $row['GPA'];
	echo "GPA: ";
	$roundedgpa = round($gpa,2);
	echo $roundedgpa;
	echo "</td>
	<td>";
	if($gpa >= 3.0){
		echo "&#10004";
	}
	else{
		echo "&#10008";
	}
	echo "</td>
	</tr>
	<td>Maximum of 2 letter grades below B-</td>
	<td>";
	$letter_grade_check = 0;
	$course_grade_check_query = "SELECT qualitypoints FROM transcripts, gradecalc
			WHERE studentid=$id AND gradecalc.grade = transcripts.grade;";
			$course_grade_check_result = mysqli_query($connection, $course_grade_check_query);
	while ($row = mysqli_fetch_assoc($course_grade_check_result)) {
		if ($row['qualitypoints'] < 2.70) {
			$letter_grade_check++;
		}
	}
	echo $letter_grade_check;
	echo " grade(s) below B-";
	echo "</td>
	<td>";
	if($letter_grade_check <= 2){
		echo "&#10004";
	}
	else{
		echo "&#10008";
	}
	echo "</td>
	</tr>
	</table>
	";





?>


</body>
</html>