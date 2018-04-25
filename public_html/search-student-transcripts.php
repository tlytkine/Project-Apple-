<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>Search for a Student</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
$allowed_user_types = array(
    "INSTRUCTOR",
    "GS",
    "ADMIN"
);
include 'header.php';

?>
<h2>Search for a Student</h2>

<?php
    if (in_array("INSTRUCTOR", $_SESSION["roles"])){
        echo '<form method="post" action="view-student-transcript.php">';
		echo '<h4>Enter a Student Name:</h4>';
		echo 'First Name: <input type="text" name="fname"><br>';
		echo 'Last Name: <input type="text" name="lname"><br>';
		echo '<input type="submit" name="transcript_name_search" value="Search">';

        echo '<h4>Enter a Student ID:</h4> <input type="text" name="id"><br>';
		echo '<input type="submit" name="transcript_search" value="Search">';
		echo '</form>';
    }
    else {
        echo '<form method="post" action="view-any-student-transcript.php">';
		echo '<h4>Enter a Student Name:</h4>';
		echo 'First Name: <input type="text" name="fname"><br>';
		echo 'Last Name: <input type="text" name="lname"><br>';
		echo '<input type="submit" name="transcript_name_search" value="Search">';

        echo '<h4>Enter a Student ID:</h4> <input type="text" name="id"><br>';
		echo '<input type="submit" name="transcript_search" value="Search">';
		echo '</form>';
    }
?>

</body>
</html>
