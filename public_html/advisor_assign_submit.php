<?php 
include 'header.php';
include 'db-connect.php';


?>

<html>
<head><title>Faculty Advisor Update</title></head>
<body>
<?php
// Faculty advisor query 
// Faculty advisor result 
// Put Faculty advisors into drop down menu with fid as hidden value 

$studentid = $_POST['studentid'];
$facultyid = $_POST['facultyid'];
 
// Deletes any values and then reserts them
// Can't use update because their might not always be an
// entry when first creating a student
$delete = "DELETE FROM advises WHERE studentid = '$studentid';";
$insert = "INSERT INTO advises (facultyid, studentid) 
	VALUES('$facultyid', '$studentid');";
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




</body>
</html>