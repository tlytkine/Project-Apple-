<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>View Schedule</title>
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

    $deactivate = $_POST["do_deactivate"];

	if($deactivate) {
		$deac_user = $_POST["deac_user"];

		/* check if user exists */
		$query = "SELECT id FROM users WHERE email = '$deac_user';";
		$result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
			$id= $row["id"];

            $query = "DELETE FROM roles WHERE id = '$id';";
			$result1 = mysqli_query($connection, $query);

            if($result1){
                $query = "INSERT INTO roles VALUES('$id', 'INACTIVE');";
			    $result2 = mysqli_query($connection, $query);

			    if($result2) {
				    echo "Successfully deactivated account";
			    } else {
				    echo "Failed to deactivate user";
			    }
            }
            else{
                echo "Failed to deactivate user";
            }
		}
        else {
			echo "User does not exist";
		}
	}
    /* deactivate account */
	else{
		echo "<p> Enter email to be deactivated </p>";
		echo "<form method='post' action='deactivate-user.php'>";
		echo    "<label for='deac_user'>Deactivate: </label>";
		echo    "<input type='text' id='deac_user' name='deac_user'/> <br/>";
		echo    "<input type='submit' value='Enter' name='do_deactivate' />";
		echo "</form>";
	}

?>

</body>
</html>
