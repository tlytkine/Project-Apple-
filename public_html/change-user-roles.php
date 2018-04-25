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

    $change_role = $_POST["change_role"];
	$add_role = $_POST["add_role"];
    $remove_role = $_POST["remove_role"];


    if($change_role) {
        $change_role_user = $_POST["change_role_user"];

        /* check if user exists */
		$query = "SELECT id FROM users WHERE email = '$change_role_user';";
		$result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
			$id= $row["id"];

            $query = "SELECT role FROM roles WHERE id = '$id';";
			$result = mysqli_query($connection, $query);

            if (mysqli_num_rows($result) > 0){
                echo "<table>";
                echo "<tr><th>Roles</th>";
                while ($row = mysqli_fetch_assoc($result)){
                    echo "<td>".$row["role"]."</td>";
                }
                echo "</tr></table>";
            }
            else{
                echo "User has no role";
            }
		}
        else {
			echo "User does not exist";
		}

		echo "<h3> Enter role to be added or removed:</h3>";
        echo "<p>Possible roles are: ADMIN (System Admin) <br />
                                     ALUMNI (Alumni) <br />
                                     STUDENT (Student) <br />
                                     APPLICANT (Applicant) <br />
                                     GS (Graduate Secretary) <br />
                                     INSTRUCTOR (Professors) <br />
                                     ADVISOR (Faculty Advisor) <br />
                                     REVIEWER (Reviewer) <br />
                                     CAC (Chair of Admissions Committee)<br /></p>";
		echo "<form method='post' action='change-user-roles.php'>";
		echo    "<label for='new_role'>New Role: </label>";
		echo    "<input type='text' id='new_role' name='new_role'/> <br/>";
		echo    "<input type='submit' value='Add' name='add_role' />";
        echo    "<input type='submit' value='Remove' name='remove_role' />";
		echo "</form>";
	}
	/*else if($do_change_role) {
		$change_role_user = $_POST["change_role_user"];
		$new_role = $_POST["new_role"];

		if (strcmp($new_role, "admin") == 0 || strcmp($new_role, "gs") == 0 || strcmp($new_role, "professor") == 0 || strcmp($new_role, "student") == 0) {
			$query = "UPDATE users SET role = '".$new_role."' WHERE username = '".$change_role_user."';";
			$result = mysqli_query($conn, $query);

			if ($result) {
				echo "<br/>";
				echo "Successfully changed user role";
			} else {
				echo "<br/>";
				echo "Failed to change user role";
			}
		} else {
			echo "<br/>";
			echo "Invalid user type";
		}
	}*/
    else{
        echo "<p> Enter username to view their current roles: </p>";
		echo "<form method='post' action='change-user-roles.php'>";
		echo    "<label for='change_role_user'>Username: </label>";
		echo    "<input type='text' id='change_role_user' name='change_role_user'/> <br/>";
		echo    "<input type='submit' value='Enter' name='change_role' />";
		echo "</form>";
    }

?>

</body>
</html>
