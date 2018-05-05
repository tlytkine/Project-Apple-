<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Applicant Home</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array(
    "Applicant"
);
include 'header.php';
?>
<h1>Review Application </h1>

<?php
include 'db-connect.php';

$id = $_SESSION['id'];
	
$query = "SELECT applicationsubmitted FROM documentstatus WHERE applicationid = $id";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_array($result);
$submitted = $row['applicationsubmitted'];

if (!$submitted) {

	$semester             = mysqli_real_escape_string($connection, trim($_POST['semester']));
	$yearapp              = $_POST['yearapp'];
	$degree               = mysqli_real_escape_string($connection, trim($_POST['degree']));
	$GRE_total            = $_POST['total'];
	$GRE_verbal           = $_POST['verbal'];
	$GRE_analytical       = $_POST['analytical'];
	$GRE_quantitative     = $_POST['quantitative'];
	$GRE_date             = $_POST['gredate'];
	$GRE_Advanced_score   = $_POST['greadvancedscore'];
	$GRE_Advanced_subject = mysqli_real_escape_string($connection, trim($_POST['subject']));
	$GRE_Advanced_date    = $_POST['greadvanceddate'];
	$TOEFL_score          = $_POST['toeflscore'];
	$TOEFL_date           = $_POST['toefldate'];
	$gpa                  = $_POST['gpa'];
	$major                = mysqli_real_escape_string($connection, trim($_POST['major']));
	$year                 = $_POST['year'];
	$university           = mysqli_real_escape_string($connection, trim($_POST['university']));
	$gpa2                 = $_POST['gpa2'];
	$major2               = mysqli_real_escape_string($connection, trim($_POST['major2']));
	$year2                = $_POST['year2'];
	$university2          = mysqli_real_escape_string($connection, trim($_POST['university2']));
	$area_of_interest     = mysqli_real_escape_string($connection, trim($_POST['areaofinterest']));
	$experience           = mysqli_real_escape_string($connection, trim($_POST['experience']));
	$writer_name          = mysqli_real_escape_string($connection, trim($_POST['writername1']));
	$writer_email         = $_POST['writeremail1'];
	$writer_affiliation   = mysqli_real_escape_string($connection, trim($_POST['affiliation1']));
	$writer_name2         = mysqli_real_escape_string($connection, trim($_POST['writername2']));
	$writer_email2        = $_POST['writeremail2'];
	$writer_affiliation2  = mysqli_real_escape_string($connection, trim($_POST['affiliation2']));
	$writer_name3         = mysqli_real_escape_string($connection, trim($_POST['writername3']));
	$writer_email3        = $_POST['writeremail3'];
	$writer_affiliation3  = mysqli_real_escape_string($connection, trim($_POST['affiliation3']));

	// If application submitted:
	if (isset($_POST['submit'])) {
		if ($degree == "MS" || ($_POST['total'] >= 130 && $_POST['verbal'] >= 130 && $_POST['quantitative'] >= 130) && isset($_POST['gredate'])) {
			$query1 = "UPDATE documentstatus 
						SET applicationsubmitted = TRUE
						WHERE applicationid = '$id'";
			$result  = mysqli_query($connection, $query1);
			echo '<h3>Application Submitted</h3>';
		} else {
			echo '<h3>Application Saved but Not Submitted - Missing Information</h3>';
			echo 'GRE scores are required for doctoral applicants<br /><br />';
		}
	// If application saved:
	} else if (isset($_POST['save'])) {
		echo '<h3>Application Saved</h3>';
	}	

	echo 'Applying for semester: ' . $semester . '<br />';
	echo 'Applying for year: ' . $yearapp . '<br />';
	echo 'Applying for Degree: ' . $degree . '<br />';
	echo 'Applicant Credentials: <br />';
	echo 'Test Scores: <br />';
	echo 'GRE Total: ' . $GRE_total . '<br />';
	echo 'GRE Sub Scores:<br />';
	echo 'Verbal: ' . $GRE_verbal . ' Analytical: ' . $GRE_analytical . ' Quantitative: ' . $GRE_quantitative . '<br />';
	echo 'GRE Advanced: <br />';
	echo 'Score: ' . $GRE_Advanced_score . ' Subject: ' . $GRE_Advanced_subject . ' Date: ' . $GRE_Advanced_date . '<br />';
	echo 'TOEFL: <br />';
	echo 'Score: ' . $TOEFL_score . ' Date: ' . $TOEFL_date . '<br />';
	echo 'Bachelors Degree:' . '<br />';
	echo 'GPA: ' . $gpa . '<br />';
	echo 'Major: ' . $major . '<br />';
	echo 'Year: ' . $year . '<br />';
	echo 'University: ' . $university . '<br />';
	echo 'Masters Degree: ' . '<br />';
	echo 'GPA: ' . $gpa2 . '<br />';
	echo 'Major: ' . $major2 . '<br />';
	echo 'Year: ' . $year2 . '<br />';
	echo 'University: ' . $university2 . '<br />';
	echo 'Supplemental Information: <br />';
	echo 'Areas of Interest: ' . $area_of_interest . '<br />';
	echo 'Experience: ' . $experience . '<br />';
	echo "Reccomendation #1: " . '<br />';
	echo "Writer Name: " . $writer_name . '<br />';
	echo "Writer Email: " . $writer_email . '<br />';
	echo "Writer Affiliation: " . $writer_affiliation . '<br />';
	echo "Reccomendation #2: " . '<br />';
	echo "Writer Name: " . $writer_name2 . '<br />';
	echo "Writer Email: " . $writer_email2 . '<br />';
	echo "Writer Affiliation: " . $writer_affiliation2 . '<br />';
	echo "Reccomendation #3: " . '<br />';
	echo "Writer Name: " . $writer_name3 . '<br />';
	echo "Writer Email: " . $writer_email3 . '<br />';
	echo "Writer Affiliation: " . $writer_affiliation3 . '<br />';

	// adds applicant credentials 
	$query3 = "UPDATE admissionsapplication 
					SET semester = '$semester',
					year = '$yearapp'
					WHERE id = '$id'";
	$result = mysqli_query($connection, $query3);

	$query2 = "UPDATE academicinfo 
					SET degreeapplyingfor = '$degree'
					WHERE applicationid = '$id'";
	$result = mysqli_query($connection, $query2);

	$query2 = "UPDATE academicinfo 
					SET gretotal = '$GRE_total',
						greverbal = '$GRE_verbal',
						greanalytical = '$GRE_analytical', 
						grequantitive = '$GRE_quantitative', 
						gredate = '$GRE_date'
					WHERE applicationid = '$id'";
	$result = mysqli_query($connection, $query2);


	// adds previous degrees 

	$query3 = "UPDATE academicinfo 
					SET bachgpa = '$gpa', 
						bachmajor = '$major', 
						bachyear = '$year', 
						bachuni = '$university'
					WHERE applicationid = '$id'";
	$result = mysqli_query($connection, $query3);

	// recommendations 

	$recquery = "SELECT recommendationid FROM recommendation WHERE applicationid = $id LIMIT 3";
	$recresult = mysqli_query($connection, $recquery);

	$row1 = mysqli_fetch_array($recresult);
	$row2 = mysqli_fetch_array($recresult);
	$row3 = mysqli_fetch_array($recresult);

	if ($row1) {
	$query4 = "UPDATE recommendation 
					SET writername = '$writer_name', 
						writeremail = '$writer_email', 
						affiliation = '$writer_affiliation'
					WHERE recommendationid = '{$row1['recommendationid']}'";
	$result = mysqli_query($connection, $query4);
	}

	// optional recommender 

	if ($row2 && isset($writer_name2) && isset($writer_email2) && isset($writer_affiliation2)) {
	$query = "UPDATE recommendation 
					SET writername = '$writer_name2', 
						writeremail = '$writer_email2', 
						affiliation = '$writer_affiliation2'
					WHERE recommendationid = '{$row2['recommendationid']}'";
	$result = mysqli_query($connection, $query);
	}

	// optional recommender #2

	if ($row3 && isset($writer_name3) && isset($writer_email3) && isset($writer_affiliation3)) {
	$query = "UPDATE recommendation 
					SET writername = '$writer_name3', 
						writeremail = '$writer_email3', 
						affiliation = '$writer_affiliation3'
					WHERE recommendationid = '{$row3['recommendationid']}'";
	$result = mysqli_query($connection, $query);
	}

	// if gre advanced scores are provided 
	if (isset($GRE_Advanced_score) && isset($GRE_Advanced_subject) && isset($GRE_Advanced_date)) {
		$query5 = "UPDATE academicinfo 
					SET greadvscore = '$GRE_Advanced_score', 
						gresubj = '$GRE_Advanced_subject', 
						greadvdate = '$GRE_Advanced_date'
					WHERE applicationid = '$id'";
		$result = mysqli_query($connection, $query5);
	}

	// if TOEFL scores are provided 

	if (isset($TOEFL_score) && isset($TOEFL_date)) {
		$query6 = "UPDATE academicinfo 
					SET toeflscore = '$TOEFL_score', 
						toefldate = '$TOEFL_date'
					WHERE applicationid = '$id'";
		$result = mysqli_query($connection, $query6);
	}

	// if masters provided 

	if (isset($gpa2) && isset($major2) && isset($year2) && isset($university2)) {
		$query7 = "UPDATE academicinfo 
					SET masgpa = '$gpa2', 
						masmajor = '$major2', 
						masyear = '$year2', 
						masuni = '$university2'
					WHERE applicationid = '$id'";
		$result = mysqli_query($connection, $query7);
	}

	// if areas of interest provided 

	if (isset($area_of_interest)) {
		$query8 = "UPDATE academicinfo 
					SET areaofint = '$area_of_interest'
					WHERE applicationid = '$id'";
		$result = mysqli_query($connection, $query8);
	}

	// if experience given 

	if (isset($area_of_interest)) {
		$query9 = "UPDATE academicinfo 
					SET experience = '$experience'
					WHERE applicationid = '$id'";
		$result = mysqli_query($connection, $query9);
	}
} else {
	echo "Application already submitted";
}

mysqli_close($connection);
?>

</body>
</html>
