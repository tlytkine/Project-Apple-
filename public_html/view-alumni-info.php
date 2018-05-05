<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>View Schedule</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
    $allowed_user_types = array(
        "USER"
    );
    include 'header.php';
    include 'db-connect.php';


    /* extract user */
	$user = $_SESSION["email"];

    $change = $_POST["change"];

	/* handle changing of personal info */
	if($change) {
		$pass = $_POST["pass"];
		$first = $_POST["first"];
		$last = $_POST["last"];
		$address = $_POST["address"];
		$email = $_POST["email"];

		/* for finding correct row */
		$user = $_SESSION["email"];
		$query = "SELECT id
					FROM users
					WHERE email = '$user';";
		$result = mysqli_query($connection, $query);
		$row = mysqli_fetch_assoc($result);
		$id = $row["id"];

		/* queries for changing info */
        if($pass) {
            // Prepare password:
            $pass = mysqli_real_escape_string($connection, trim($pass));

            // Hash password:
        	$hash = password_hash($pass, PASSWORD_BCRYPT);

            $query = "UPDATE users SET password='$hash' WHERE email='$user';";

			$result = mysqli_query($connection, $query);

			if($result) {
				echo "Successfully updated password";
				echo "</br>";

			} else {
				echo "Failed to update password";
				echo "</br>";
			}
		}
		if($first) {
			$query = "UPDATE alumnipersonalinfo
					SET firstname='".$first."'
					WHERE id='".$id."';";

			$result = mysqli_query($connection, $query);

			if($result) {
				echo "Successfully updated first name";
				echo "</br>";
			} else {
				echo "Failed to update fist name";
				echo "</br>";
			}
		}
		if($last) {
			$query = "UPDATE alumnipersonalinfo
					SET lastname='".$last."'
					WHERE id='".$id."';";

			$result = mysqli_query($connection, $query);

			if($result) {
				echo "Successfully updated last name";
				echo "</br>";
			} else {
				echo "Failed to update last name";
				echo "</br>";
			}
		}
		if($address) {
			$query = "UPDATE alumnipersonalinfo SET address='".$address."' WHERE id='".$id."';";

			$result = mysqli_query($connection, $query);

			if($result) {
				echo "Successfully updated address";
				echo "</br>";
			} else {
				echo "Failed to update address";
				echo "</br>";
			}
		}
		if($email) {
			$query = "UPDATE users SET email='".$email."' WHERE id='".$id."';";

			$result = mysqli_query($connection, $query);

            $user = $email;
            $_SESSION["email"] = $email;

			if($result) {
				echo "Successfully updated email";
				echo "</br>";
			} else {
				echo "Failed to update email";
				echo "</br>";
			}
		}
	}
    /* handle update personal info display */
	else {
		echo "<h2 style='text-align:center'> Update Personal Info </h2>";
		echo "<p> This is the information you have currently entered</p>";
		$query = "SELECT p.id, p.firstname, p.lastname, p.address, u.email, p.graduationyear
			      FROM  alumnipersonalinfo p, users u
			      WHERE p.id = u.id AND u.email = '$user';";
		$result = mysqli_query($connection, $query);
		if (mysqli_num_rows($result)>0) {
			echo '<table style="width:25%"';
			echo '<tr>';
			echo '<th>Student ID</th>';
			echo '<th>First Name</th>';
			echo '<th>Last Name</th>';
			echo '<th>Address</th>';
			echo '<th>Email</th>';
			echo '<th>Graduation Year</th>';
			echo '</tr>';
			while($row = mysqli_fetch_assoc($result)) {
				echo '<tr>';
				echo'<td>' . $row["id"] . '</td>';
				echo'<td>' . $row["firstname"] . '</td>';
				echo'<td>' . $row["lastname"] . '</td>';
				echo'<td>' . $row["address"] . '</td>';
				echo'<td>' . $row["email"] . '</td>';
				echo'<td>' . $row["graduationyear"] . '</td>';
				echo '</tr>';
			}
			echo '</table><br />';
		}
		echo "<p> Fill in what you would like to change </p>";
		echo "<p> Leave all else blank, then hit Change </p>";

		echo "<form method='post' action='view-info.php'>";
		echo    "<label for='pass'>Password: </label>";
		echo    "<input type='text' id='pass' name='pass' /> <br/>";
		echo    "<label for='first'>First name: </label>";
		echo    "<input type='text' id='first' name='first' /> <br/>";
		echo    "<label for='last'>Last name: </label>";
		echo    "<input type='text' id='last' name='last' /> <br/>";
		echo    "<label for='address'>Address: </label>";
		echo    "<input type='text' id='address' name='address' /> <br/>";
		echo    "<label for='email'>Email: </label>";
		echo    "<input type='text' id='email' name='email' /> <br/>";
		echo    "<input type='submit' value='Change' name='change' />";
		echo "</form>";
	}

?>

</body>
</html>
