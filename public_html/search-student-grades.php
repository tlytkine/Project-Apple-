<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>Search for a Student</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
$allowed_user_types = array(
    "INSTRUCTOR"
);
include 'header.php';

?>
<h2>Search for a Student</h2>

<form method="post" action="public_html/change_student_grades.php">
<h4>Enter a Student Name:</h4>
First Name: <input type="text" name="fname"><br>
Last Name: <input type="text" name="lname"><br>
<input type="submit" name="grade_name_search" value="Search">

<h4>Enter a Student ID:</h4> <input type="text" name="student_id"><br>
<input type="submit" name="grade_search" value="Search">
</form>

</body>
</html>
