<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Manage Letters of Recommendation</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("APPLICANT");
include 'header.php';
?>

<h1>Manage Letters of Recommendation</h1>

<?php
include 'db-connect.php';

$query = "SELECT recommendationid, writername, writeremail, affiliation, ISNULL(letterfile)
	FROM recommendation
	WHERE applicationid = {$_SESSION['id']}";
$result = mysqli_query($connection, $query);
if (mysqli_num_rows($result) > 0) {
	echo "<table>
		<tr>
		<th>ID</th>
		<th>Name</th>
		<th>Email</th>
		<th>Affiliation</th>
		<th>Status</th>
		<th>Options</th>
		</tr>";
	while ($row = mysqli_fetch_assoc($result)) {
		$letterstatus = ($row['ISNULL(letterfile)'] == 1) ? "&#x2718; Not Received" : "&#x2714; Received";
		echo "<tr>
			<td>{$row['recommendationid']}</td>
			<td>{$row['writername']}</td>
			<td>{$row['writeremail']}</td>
			<td>{$row['affiliation']}</td>
			<td>{$letterstatus}</td>
			<td><form method='POST'>
			<input type='hidden' name='recid' value='{$row['recommendationid']}'>
			<input type='hidden' name='writeremail' value='{$row['writeremail']}'>
			<button type='submit' name='send'>Send Link</button>
			</form></td>
			</tr>";
	}
	echo "</table>";
} else {
	echo "No data";
}

if (isset($_POST['send'])) {
	$recid = $_POST['recid'];
	$email = $_POST['writeremail'];
	$reclink = $path . "upload-recommendation.php?recid=" . $recid;
	$message = "Greetings,\n\nPlease use the following link to submit your letter of recommendation:\n";
	$message = $message . $reclink;
	$message = $message . "\n\nThank you,\nGW Admissions";
	$body = wordwrap($message, 70);
	$headers = "From: GW Admissions <do-not-reply@example.com>";
	mail($email, "GW Admissions Recommendation Request", $body, $headers);
	echo "Link sent to " . $email;
}

?>
</body>
</html>