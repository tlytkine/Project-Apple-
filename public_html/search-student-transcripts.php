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

<form method="post" action="view-student-transcript.php">
<h4>Enter a Student Name:</h4>
First Name: <input type="text" name="fname"><br>
Last Name: <input type="text" name="lname"><br>
<input type="submit" name="transcript_name_search" value="Search">

<h4>Enter a Student ID:</h4> <input type="text" name="id"><br>
<input type="submit" name="transcript_search" value="Search">
</form>


</body>
</html>