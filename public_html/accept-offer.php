<html lang="en-US">
<head>
  <meta charset="UTF-8">
  <title>Accept Admissions Offer</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("APPLICANT");
include 'header.php';
?>

<h1>Accept Admissions Offer</h1>
<h4>Deposit: $500</h4>
<form method="POST" id="acceptoffer">
    <label for='name'>Name (as it appears on your card) </label><br/>
    <input type='text' id='name' name='name' required/> <br/><br />
    <label for='card'>Card Number (no dashes or spaces) </label><br/>
    <input type='number' id='card' name='card' required/> <br/><br />
    <label for='date'>Expiration Date </label><br/>
    <select name="month">
        <option>January</option>
        <option>February</option>
        <option>March</option>
        <option>April</option>
        <option>May</option>
        <option>June</option>
        <option>July</option>
        <option>August</option>
        <option>September</option>
        <option>October</option>
        <option>November</option>
        <option>December</option>
    </select>
    <input type='number' id='year' name='year' required/> <br/><br />
    <label for='code'>Security Code </label><br/>
    <input type='number' id='code' name='code' required/> <br/><br />
    <label for='zip'>Zipcode </label><br/>
    <input type='number' id='zip' name='zip' required/> <br/><br />
	<input type="submit" name="accept" value="Accept and Pay Deposit">
</form>

<?php
include 'db-connect.php';

$id = $_SESSION['id'];

if (isset($_POST['accept'])) {
	$query = "INSERT INTO roles(id, role) VALUES ($id, 'STUDENT')";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Offer could not be accepted');</script>";
		exit();
	}
	$query = "DELETE FROM roles WHERE id=$id AND role='APPLICANT'";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Offer could not be accepted');</script>";
		exit();
	}

	// Reset roles session variable:
	$query = "SELECT role FROM roles WHERE id={$_SESSION['id']}";
	$result = mysqli_query($connection, $query);
	$roles = array();
	while ($row = mysqli_fetch_array($result)) {
		 $roles[] = $row["role"];
	}
	$_SESSION["roles"] = $roles;

	// Get degree name and year:
	$query = "SELECT degreeapplyingfor, year
		FROM academicinfo, admissionsapplication
		WHERE academicinfo.applicationid = admissionsapplication.id AND applicationid = $id";
	$result = mysqli_query($connection, $query);
	$row = mysqli_fetch_array($result);
	$degree = $row["degreeapplyingfor"];
	$year = $row["year"];

	// Add hold:
	$query = "INSERT INTO advises (studentid, hold, degreename, admityear) VALUES ($id, 'New Student', '$degree', $year)";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Error adding hold');</script>";
		exit();
	}

	// Add to personal info table:
	$query = "INSERT INTO personalinfo (id, firstname, lastname, dob, address, ssn)
	SELECT id, firstname, lastname, dob, address, ssn
	FROM applicantpersonalinfo
	WHERE id = $id";
	$result = mysqli_query($connection, $query);
	if (!$result) {
		echo "<script type='text/javascript'>alert('Error adding student');</script>";
		exit();
	}

	// Welcome message and redirect:
	echo "<script type='text/javascript'>alert('Congratulations and welcome to the George Washington University! Go Colonials!');</script>";
	header("refresh:0 url=menu.php");
}

?>

</body>
</html>
