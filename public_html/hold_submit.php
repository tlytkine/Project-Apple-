<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSON["id"];
?>

<html>
<head><title>View / Edit Holds </title></head>
<body>
<?php

$studentid = $_POST['studentid'];
$facultyid = $_POST['facultyid'];



# Lift Hold Button 
if (isset($_POST['lift'])) {
	echo "<h1>Lift Hold</h1><br>";
	$lift_query = "UPDATE advises
	SET hold='NULL'
	WHERE studentid = '$studentid' AND 
	facultyid = '$facultyid'";
	$lift_result = mysqli_query($conn, $lift_query);
	if(mysqli_affected_rows($conn)>0){
		echo "Hold was successfully lifted!<br>";
	}
	else {
		echo "There was not a hold in place to be lifted.<br>";
	}
}



# Place Hold Button 
if (isset($_POST['place'])) {
	echo "<h1>Place Hold</h1><br>";
	if(isset($_POST['holdtext'])){
		$holdtext = $_POST['holdtext'];
		if(strlen($holdtext)>0){
			$place_query = "UPDATE advises
			SET hold='$holdtext'
			WHERE studentid = '$studentid' AND 
			facultyid = '$facultyid'";

			$place_result = mysqli_query($conn, $place_query);
			if(mysqli_affected_rows($conn)>0){
				echo "Hold was successfully placed!<br>";
			}
			else {
				echo "Please try a different hold.<br>";
			}
		}
		else {
			echo "Hold was not placed. Please enter a hold in the text field.<br>";
		}
	}
}






?>
<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Back</a><br>
</body>
</html>