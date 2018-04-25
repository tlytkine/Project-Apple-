<?php
	session_start();

	$login = $_POST["login"];
	$reset = $_POST["reset"];

	$user = $_POST['email'];
	$pass = $_POST['password'];

	$_SESSION['email'] = $user;

	$servername = "127.0.0.1";
	$username = "teamA2";
	$password = "Ar9x5Y";
	$dbname = "teamA2";

	$conn = mysqli_connect($servername, $username, $password, $dbname);

	if($login){
		$query = "SELECT *
            	FROM users u , roles r
            	WHERE u.id = r.id AND u.email = '".$user."';";

		$result = mysqli_query($conn, $query);
		$row = mysqli_fetch_assoc($result);

		$password = $row["password"];
		/* inside if statements below, add session variable storing role */

		if (/*password_verify($pass, $hash)*/ strcmp($password, $pass) == 0) {

			$_SESSION["role"] = $row["role"];

			if ($row["role"] == 'student'){
				header("Location:student.html"); //change file name
			}
			else if ($row["role"] == 'gs'){
				header("Location:gs.html"); //change file name
			}
			else if ($row["role"] == 'professor'){
				header("Location:prof.html"); //change file name
			}
			else if ($row["role"] == 'admin'){
				header("Location:admin.html"); //change file name
			}
			else if ($row["role"] == 'inactive'){
				echo "Account Deactivated";
			}
			else{
				header("Location:home.html"); //change file name
			}
  		}
  		else {
				echo "please try again";
  		}
	}
  if($reset){
	$query = file_get_contents("database_setup.sql");
	if(mysqli_multi_query($conn, $query)) {
		do {
			if($result = mysqli_store_result($conn)) {
				while($row = mysqli_fetch_row($result)){}
				mysqli_free_result($conn);
			}
		} while(mysqli_next_result($conn));
	}

	/* update users table to store hash
	 * RIP */
	/*$queryUsers = "SELECT * FROM users;";
	$getUsers = mysqli_query($conn, $queryUsers);
	if (!$getUsers) {
		echo "error: ". mysqli_error($conn) . "<br/>";
	}
	$numRows = mysqli_num_rows($getUsers);

	for($i = 0; $i < $numRows; $i++){
		$row = mysqli_fetch_assoc($getUsers);
		$getPass = $row["password"];
		$passHash = password_hash($getPass, PASSWORD_BCRYPT);

		$query = "UPDATE users SET password = '".$passHash."' WHERE username = '".$row["username"]."';";
		$storedHash = mysqli_query($conn, $query);
	}*/
	  echo "<h3>Successfully reset database</h3>";
  }
  mysqli_close($conn);
?>
