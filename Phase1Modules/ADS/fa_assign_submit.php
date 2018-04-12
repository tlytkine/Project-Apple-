<?php
	// Handles assigning a student a faculty advisor

	// Login script
	session_start();
	$conn = mysqli_connect("localhost", "team5", "9GcBpHaf", "team5");

	$user_check=$_SESSION['login_user'];
	$ses_sql=mysqli_query($conn, "select username from login where username='$user_check' AND role = 'GRAD_SECRETARY'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session = $row['username'];
	if (!isset($login_session)) {
		mysqli_close($conn);
		header("Location: wrong_permissions.php");
		exit;
	}





?>
<html>
<head><title>Faculty Advisor Update</title></head>
<link rel ="stylesheet" type="text/css" href="style1.css"/>
<body>
<?php

// Faculty advisor query 
// Faculty advisor result 
// Put Faculty advisors into drop down menu with fid as hidden value 

$gwid = $_POST['gwid'];
$fid = $_POST['fid'];
 
// Deletes any values and then reserts them
// Can't use update because their might not always be an
// entry when first creating a student
$delete = "DELETE FROM advises WHERE gwid = '$gwid';";
$insert = "INSERT INTO advises (fid, gwid) 
	VALUES('$fid', '$gwid');";
$result1 = mysqli_query($conn, $delete);
$result2 = mysqli_query($conn, $insert);

if ($result2) {
	echo "Successful <br>";
} else {
	echo $delet . "<br>";
	echo $insert . "<br>";
}

/*$update_query = "UPDATE advises
SET fid = '$fid' 
WHERE gwid = '$gwid';";


$update_result = mysqli_query($conn, $update_query);
$x = mysqli_affected_rows($conn);
if($x>0){
	echo "Faculty advisor successfully updated.<br>";

}
else if($x==0){
	echo "The faculty advisor selected was already assigned to the student. No updates were made.<br>";
}
else if($x==-1){
	$query = "INSERT INTO advises(gwid,fid)
			  VALUES('$gwid','$fid');";
	$result = mysqli_query($conn, $query);
	if(mysqli_affected_rows($conn)>0){
		echo "Faculty advisor successfully assigned.<br>";
	}
	else {
		echo "Faculty advisor assignment was not successful.<br>";
	}
}
*/


?>



<b><a href="gs.php">Go back</a></b>


</body>
</html>