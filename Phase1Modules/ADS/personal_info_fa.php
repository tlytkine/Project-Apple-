<?php 
include 'header.php';
include 'db-connect.php';
$id = $_SESSON["id"];
?>
<html>
<head><title>Personal Information</title></head>
<body>
<h1>Personal Information: <br></h1>
<?php
// display personal information, cannot edit
echo "First Name: ".$first_name."<br>";
echo "Last Name: ".$last_name."<br>";
echo "ID: ".$id."<br>";
echo "Email: ".$email."<br>";
echo "Address: ".$address."<br>";
echo "DOB: ".$dob."<br>"
?>

</form>
<a href='logout.php'>Logout</a>
<button onclick="history.go(-1);">Back </button>
</body>
</html>
