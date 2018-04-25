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
            $_SESSION["change_role_id"] = $id;

            $query = "SELECT role FROM roles WHERE id = '$id';";
			$result = mysqli_query($connection, $query);

            echo "<h3>".$change_role_user."</h3>";
            if (mysqli_num_rows($result) > 0){
                echo "<br />";
                echo "<table>";
                echo "<tr><th>Roles</th>";
                while ($row = mysqli_fetch_assoc($result)){
                    echo "<td style='border-right: solid black'>".$row["role"]."</td>";
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
        echo "<p>Possible roles are: <br />
                    ADMIN (System Admin) <br />
                    ALUMNI (Alumni) <br />
                    STUDENT (Student) <br />
                    APPLICANT (Applicant) <br />
                    GS (Graduate Secretary) <br />
                    INSTRUCTOR (Professors) <br />
                    ADVISOR (Faculty Advisor) <br />
                    REVIEWER (Reviewer) <br />
                    CAC (Chair of Admissions Committee)<br /></p>";
		echo "<form method='post' action='change-user-roles.php'>";
		echo    "<label for='role'>Role: </label>";
		echo    "<input type='text' id='role' name='role'/> <br/>";
		echo    "<input type='submit' value='Add' name='add_role' />";
        echo    "<input type='submit' value='Remove' name='remove_role' />";
		echo "</form>";
	}
	else if($remove_role) {
		$role = $_POST["role"];
        $role = strtoupper($role);
        $id = $_SESSION["change_role_id"];
        $user_types = array(
            "ADMIN",
            "ALUMNI",
            "STUDENT",
            "APPLICANT",
            "GS",
            "INSTRUCTOR",
            "ADVISOR",
            "REVIEWER",
            "CAC"
        );

		if (in_array($role, $user_types)) {
			$query = "DELETE FROM roles WHERE id = '$id' AND role = '$role';";
			$result = mysqli_query($connection, $query);

			if ($result) {
				echo "<br/>";
				echo "Successfully removed user role";
			} else {
				echo "<br/>";
				echo "User doesn't have that role";
			}
		} else {
			echo "<br/>";
			echo "Invalid role";
		}
	}
    else if($add_role) {
		$role = $_POST["role"];
        $role = strtoupper($role);
        $id = $_SESSION["change_role_id"];
        $user_types = array(
            "ADMIN",
            "ALUMNI",
            "STUDENT",
            "APPLICANT",
            "GS",
            "INSTRUCTOR",
            "ADVISOR",
            "REVIEWER",
            "CAC"
        );

		if (in_array($role, $user_types)) {
            $query = "DELETE FROM roles WHERE id = '$id' AND role = 'INACTIVE';";
			$result = mysqli_query($connection, $query);

			$query = "INSERT INTO roles VALUES ('$id', '$role');";
			$result = mysqli_query($connection, $query);

			if ($result) {
				echo "<br/>";
				echo "Successfully added user role";
			} else {
				echo "<br/>";
				echo "User already has that role";
			}
		} else {
			echo "<br/>";
			echo "Invalid role";
		}
	}
    else{
        echo "<p> Enter username to view their current roles: </p>";
		echo "<form method='post' action='change-user-roles.php'>";
		echo    "<label for='change_role_user'>Email: </label>";
		echo    "<input type='text' id='change_role_user' name='change_role_user'/> <br/>";
		echo    "<input type='submit' value='Enter' name='change_role' />";
		echo "</form>";
    }

?>

</body>
</html>
