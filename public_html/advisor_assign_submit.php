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
$insert = "UPDATE advises SET facultyid = '$facultyid'
WHERE studentid = '$studentid';";
$result2 = mysqli_query($connection, $insert);

if ($result2) {
	echo "Faculty advisor successfully updated.<br>";
} else {
	echo $insert . "<br>";
}


?>




</body>
</html>