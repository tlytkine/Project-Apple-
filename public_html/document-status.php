<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Update Document Status</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("GS", "ADMIN");
include 'header.php';
?>
<h1>Update Document Status</h1>

<form method="POST" id="idsearch">
	<h3>Search:</h3>
    Application ID: <input type="number" min=1 max=2147483647 name="id">
    <input type="submit" name="idsubmit" value="Search by ID">
    Last Name: <input type="text" max=30 name="lastname">
    <input type="submit" name="namesubmit" value="Search by Name">
    <input type="submit" name="viewall" value="View All">
</form>
<h3>Results:</h3>
<?php
	function showTranscriptStatus($row) {
		$transcript_status = $row["transcriptrecieved"];
		$selected0 = ($transcript_status == 0) ? "selected" : "";
		$selected1 = ($transcript_status == 1) ? "selected" : "";
		echo "<select name=\"transcriptrecieved\" required>
			<option value=0 $selected0>Not Received</option>
			<option value=1 $selected1>Received</option>
		</select>";
		echo "<input type=\"hidden\" name=\"id\" value=\"{$row["applicationid"]}\">";
	}

	function showLetterOfRecStatus($row) {
		$letter_of_rec_status = $row["letterofrecrecieved"];
		$selected0 = ($letter_of_rec_status == 0) ? "selected" : "";
		$selected1 = ($letter_of_rec_status == 1) ? "selected" : "";
		echo "<select name=\"letterofrecrecieved\" required>
			<option value=0 $selected0>Not Received</option>
			<option value=1 $selected1>Received</option>
		</select>";
	}

	include 'db-connect.php';
	$query = "SELECT documentstatus.applicationid, personalinfosubmitted, applicationsubmitted, transcriptrecieved, letterofrecrecieved, firstname, lastname, status
		FROM documentstatus, applicantpersonalinfo, admissionsapplication
		WHERE documentstatus.applicationid = applicantpersonalinfo.id AND  documentstatus.applicationid = admissionsapplication.id";
		
	if (!isset($_POST['viewall']) && isset($_POST['idsubmit']) && $_POST['id'] > 0) {
		$id = mysqli_real_escape_string($connection, trim($_POST['id']));
		$query = $query . " AND applicantpersonalinfo.id = $id";
	} else if (!isset($_POST['viewall']) && isset($_POST['namesubmit']) && isset($_POST['lastname'])) {
		$name = $_POST['lastname'];
		$query = $query . " AND lastname LIKE '%$name%'";
	}

	$result = mysqli_query($connection, $query);

	if (mysqli_num_rows($result) > 0) {
		echo "<table>
			<tr>
			<th>ID</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Personal <br />Information</th>
			<th>Application</th>
			<th>Transcript</th>
			<th>Letters of <br />Recommendation</th>
			<th>Status</th>
			<th></th>
			</tr>";
		while ($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<form method=\"POST\">";
			echo "<td>{$row["applicationid"]}</td>";
			echo "<td>{$row["firstname"]}</td>";
			echo "<td>{$row["lastname"]}</td>";
			echo "<td>" . ($row["personalinfosubmitted"] ? "Submitted" : "Not Submitted") . "</td>";
			echo "<td>" . ($row["applicationsubmitted"] ? "Submitted" : "Not Submitted") . "</td>";
			echo "<td>"; showTranscriptStatus($row); "</td>";
			echo "<td>"; showLetterOfRecStatus($row); "</td>";
			echo "<td>{$row["status"]}</td>";
			echo "<td><button type=\"submit\" name=\"update\">Update</button></form></td>";
			echo "</tr>";
		}
		echo "</table><br/>";
		
		if (isset($_POST['update'])) {
			$transcript_status = $_POST['transcriptrecieved'];
			$letter_of_rec_status = $_POST['letterofrecrecieved'];
			$id = $_POST['id'];
			$query = "UPDATE documentstatus
				SET transcriptrecieved=$transcript_status, letterofrecrecieved=$letter_of_rec_status
				WHERE applicationid=$id";
			$result = mysqli_query($connection, $query);
			if ($result) {
				header("refresh:0");
			} else {
				echo "<script type='text/javascript'>alert('Status could not be updated');</script>";
			}
		}
	} else {
		echo "No applications available";
	}
?>



</body>
</html>