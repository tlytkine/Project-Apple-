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
        $id;

		/* check if user exists */
		$query = "SELECT id FROM users WHERE email = '".$deac_user."';";
		$result = mysqli_query($connection, $query);
		$exists = mysqli_num_rows($result);    /* 1 if exists, 0 if otherwise */
        if($exists == 0) {
			echo "User does not exist";
		}
        else {
            $row = mysqli_fetch_assoc($result);
			$id= $row["id"];

			$query = "UPDATE users SET role = 'INACTIVE' WHERE id = '$id';";
			$result = mysqli_query($connection, $query);

			if($result) {
				echo "Successfully deactivated account";
			} else {
				echo "Failed to deactivate user";
			}
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
