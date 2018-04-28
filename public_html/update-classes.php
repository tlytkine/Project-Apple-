<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>Update Classes</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
    $allowed_user_types = array(
        "ADMIN"
    );
    include 'header.php';


    //include 'db-connect.php';
    $servername = "127.0.0.1";
    $serverusername = "teamA2";
    $serverpassword = "Ar9x5Y";
    $dbname = "teamA2";
    //$script = "../../mysql/bin/tables.sql";
    $connection = mysqli_connect($servername, $serverusername, $serverpassword, $dbname);
    if (mysqli_connect_errno()) {
    	echo "Database connection error: " . mysqli_connect_error();
    }

    /* used for page display */
    $update_classes = $_POST["update_classes"];

	if($update_classes){
		$cur_semester;
		$cur_year;

		$query = "SELECT c.year, c.semester
					FROM courses c;";

		$result = mysqli_query($connection, $query);
		if (mysqli_num_rows($result) > 0){
			$row = mysqli_fetch_assoc($result);
			$cur_semester = $row["semester"];
			$cur_year = $row["year"];
		}

		/* store the new semester and year */
		$new_semester = $_POST["new_semester"];
  	  	$new_year = $_POST["new_year"];
		$valid = 0;
		if(strcmp($cur_semester, "fall") == 0 && is_numeric($new_year) &&
			strcmp($cur_semester, $new_semester) != 0 && (int)$new_year > (int)$cur_year){
			$valid = 1;
		}
		if(strcmp($cur_semester, "spring") == 0 && is_numeric($new_year) &&
			strcmp($cur_semester, $new_semester) != 0 && (int)$new_year == (int)$cur_year){
			$valid = 1;
		}

		if($valid == 1){

			$query = "UPDATE courses
                  	  SET semester = '$new_semester', year = '$new_year';";

        	$result = mysqli_query($connection, $query);
			echo "<h3>Successfully updated courses</h3>";
		}
		else{
			echo "<h3>Invalid new semester or year please try again</h3>";
		}
	}
    else{
		echo '<form method="post" action="update-classes.php">';
		echo '<h4>Enter the new Semester</h4>';
		echo "<input type='radio' name='new_semester' value='fall'>Fall";
		echo "<input type='radio' name='new_semester' value='spring'>Spring <br/>";
		echo '<h4>Enter the new Year</h4> <input type="text" name="new_year"><br><br>';
		echo '<input type="submit" name="update_classes" value="Update">';
		echo '</form>';
	}

?>

</body>
</html>
