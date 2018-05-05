<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Update Final Decisions</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("Grad Secretary", "CAC");
include 'header.php';
?>
<h1>Update Final Decisions</h1>

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
	function showFinalDecision($row) {
		$finaldecision = $row["finaldecision"];
		$selected0 = ($finaldecision == 0) ? "selected" : "";
		$selected1 = ($finaldecision == 1) ? "selected" : "";
		$selected3 = ($finaldecision == 3) ? "selected" : "";
		$selected4 = ($finaldecision == 4) ? "selected" : "";
		echo "<form method=\"POST\">";
		echo "<select name=\"decision\" required>
			<option value=0 $selected0></option>
			<option value=1 $selected1>Reject</option>
			<option value=3 $selected3>Admit without Aid</option>
			<option value=4 $selected4>Admit with Aid</option>
		</select>";
		echo "<input type=\"hidden\" name=\"id\" value=\"{$row["id"]}\">";
		echo "<button type=\"submit\" name=\"update\">Update</button>";
		echo "</form>";
	}

	include 'db-connect.php';
	$query = "SELECT admissionsapplication.id, status, finaldecision, firstname, lastname
		FROM admissionsapplication, applicantpersonalinfo
		WHERE admissionsapplication.id = applicantpersonalinfo.id";
		
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
			<th>Status</th>
			<th>Options</th>
			<th>Final Decision</th>
			</tr>";
		while ($row = mysqli_fetch_array($result)) {
			echo "<tr>";
			echo "<td>{$row["id"]}</td>";
			echo "<td>{$row["firstname"]}</td>";
			echo "<td>{$row["lastname"]}</td>";
			echo "<td>{$row["status"]}</td>";
			echo "<td><a href=\"view-application.php?viewapplication={$row["id"]}\">Application</a> ";
			echo "<a href=\"view-review.php?view={$row["id"]}\">Review</a></td>";
			echo "<td>"; showFinalDecision($row); "</td>";
			echo "</tr>";
		}
		echo "</table><br/>";
		
		if (isset($_POST['update'])) {
			$decision = $_POST['decision'];
			$id = $_POST['id'];
			$query = "UPDATE admissionsapplication
				SET finaldecision=$decision, status='Decided'
				WHERE id=$id";
			$result = mysqli_query($connection, $query);
			if ($result) {
				header("refresh:0");
			} else {
				echo "<script type='text/javascript'>alert('Account could not be created');</script>";
			}
		}
	} else {
		echo "No applications available";
	}
?>

</body>
</html>