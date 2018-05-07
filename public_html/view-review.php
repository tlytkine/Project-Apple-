<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Review Form</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("CAC", "GS", "ADMIN");
include 'header.php';
?>

<?php
include 'db-connect.php';
function show_recommendation_ratings($connection, $applicationid, $reviewerid) {
	//recommendation letters submitted for the application
    echo "<h4>Recommendation Letters Ratings: </h4>";
    $checklettersquery = "SELECT * FROM recommendationreview, recommendation WHERE recommendationreview.recommendationid=recommendation.recommendationid AND recommendation.applicationid=$applicationid AND recommendationreview.reviewerid = $reviewerid";
    $checkresult       = mysqli_query($connection, $checklettersquery);
    
    if (mysqli_num_rows($checkresult) > 0) {
        while ($row = mysqli_fetch_assoc($checkresult)) {
            echo "<h5>Recommendation ID: " . $row['recommendationid'] . "</h5>";
            echo "Writer: " . $row['writername'] . " (" . $row['writeremail'] . ")<br>";
            echo "Affiliation: " . $row['affiliation'] . "<br>";
            if ($row['rating'] == NULL) {
                echo "No rating has been submitted yet<br>";
            } else {
                echo "Rating: " . $row['rating'] . " ";
                echo "Generic Rating: " . $row['genericrating'] . " ";
                echo "Credible Rating: " . $row['crediblerating'] . "<br>";
            }
        }
    } else {
        echo "No letter ratings";
    }
}

if(isset($_GET['view'])) {
	// Showing reviews submitted for the current selected application
	$_SESSION['currentreviewid'] = $_GET['view'];
	echo "<h1>Submitted Reviews</h1>";
	$checkreviewquery = "SELECT * FROM review, personalinfo WHERE applicationid = {$_SESSION['currentreviewid']} AND reviewerid = id";
	$checkreview = mysqli_query($connection,$checkreviewquery);
	if (mysqli_num_rows($checkreview) > 0) {
		while ($row = mysqli_fetch_assoc($checkreview)) {
			echo "<h3>Reviewer: {$row['firstname']} {$row['lastname']}</h3>";
			if ($row['decision'] == 1) {
				echo "Reviewer's Decision: Reject"; 
			} else if ($row['decision'] == 2) {
				echo "Reviewer's Decision: Borderline admit"; 
			} else if ($row['decision'] == 3) {
				echo "Reviewer's Decision: Admit without aid"; 
			} else if ($row['decision'] == 4) {
				echo "Reviewer's Decision: Admit with aid"; 
			}
			echo "<br>";
			if ($row['reasons'] == "A") {
				echo "Reason For Rejection: Incomplete record<br>";
			} else if ($row['reasons'] == "B") {
				echo "Reason For Rejection: Incomplete record<br>";
			} else if ($row['reasons'] == "C") {
				echo "Reason For Rejection: Does not meet minimum requirements<br>";
			} else if ($row['reasons'] == "D") {
				echo "Reason For Rejection: Problems with recommendation letters<br>"; 
			} else if ($row['reasons'] == "E") {
				echo "Reason For Rejection: Not competitive enough<br>";
			} else if ($row['reasons'] == "F") {
				echo "Reason For Rejection: Other reason<br>";
			}
			if ($row['decision'] != 1) {
				echo "Deficiency Courses: " . $row['defcourse'] . "<br>";
			}
			echo "Reviewer's Comments: " . $row['comments'] . "<br>";
			show_recommendation_ratings($connection, $_SESSION['currentreviewid'], $row['reviewerid']);
			echo "<br />";
		}
	} else {
		echo "No review has been submitted yet";
	}
}
?>
</body>
</html>
