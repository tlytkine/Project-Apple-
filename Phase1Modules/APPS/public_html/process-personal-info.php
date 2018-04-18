<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Applicant Home</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("Applicant");
include 'header.php';
?>
<h1>Review Personal Information </h1>

<a href="applicant-home.php">Back to Home</a> <br />


<?php
    include 'db-connect.php';
	
    $first_name = mysqli_real_escape_string($connection, trim($_POST['firstname']));
    $last_name = mysqli_real_escape_string($connection, trim($_POST['lastname']));
    $DOB = mysqli_real_escape_string($connection, trim($_POST['dob']));
    $ssn = mysqli_real_escape_string($connection, trim($_POST['ssn']));
    $address = mysqli_real_escape_string($connection, trim($_POST['address']));
    $id = $_SESSION['id'];
    
    echo 'Thanks for submitting your personal information.<br />';
    echo 'Application ID: '.$id.'<br />';
    echo 'Name: ' . $first_name . ' ' . $last_name . '<br />';
    echo 'Date of Birth: ' . $DOB .'<br />';
    echo 'Social Security Number: '.$ssn.'<br />';

    echo 'Address: ' . $address .'<br />';
            
    $query = "INSERT INTO personalinfo (firstname, lastname, dob, address, ssn, applicationid) 
            VALUES ('$first_name', '$last_name', '$DOB', '$address', '$ssn', '$id')";
    $result = mysqli_query($connection, $query);


    if ($result) {
        echo  "Personal information submitted successfully! <br/>";
		$query = "UPDATE documentstatus 
		SET personalinfosubmitted = TRUE
		WHERE applicationid = '$id'";
		$result  = mysqli_query($connection, $query);
    }
    else {
    echo "Error: " . $query . "<br>" . mysqli_error($connection);
    }
    mysqli_close($connection);
?>

</body>
</html>

