<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>Review Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array(
    "REVIEWER",
    "CAC"
);
include 'header.php';

?>
<h2>Graduate Admission Review Form</h2>

<?php
include 'db-connect.php';
echo "<hr>";
if (isset($_GET['review'])) {
    $_SESSION['currentid'] = $_GET['review'];
    
    //updating application's reviewer's username
    $reviewerusename = $_SESSION["email"];
    $updatereviewer  = " UPDATE admissionsapplication SET reviewerusername='" . $_SESSION['email'] . "' WHERE id = '" . $_SESSION['currentid'] . "'";
    
    if (mysqli_query($connection, $updatereviewer)) {
        echo " ";
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
    
    //Query from academic information an application - printing out the review form
    $query2   = "SELECT * FROM admissionsapplication, academicinfo, applicantpersonalinfo  WHERE admissionsapplication.id = applicantpersonalinfo.id AND admissionsapplication.id= academicinfo.applicationid AND admissionsapplication.id='" . $_SESSION['currentid'] . "'";
    $result_2 = mysqli_query($connection, $query2);
    if (mysqli_num_rows($result_2) > 0) {
        while ($row = mysqli_fetch_assoc($result_2)) {
            echo "Applying for Degree: " . $row['degreeapplyingfor'] . "<br>";
            echo "Term: " . $row["semester"] . "";
            echo "  " . $row["year"] . "<br>";
            
            echo "<h3> Personal Information </h3>";
            
            echo "Application ID:   " . $row["applicationid"] . "<br>";
            echo "First Name:   " . $row["firstname"] . "<br>";
            echo "Last Name:   " . $row["lastname"] . "<br>";
            echo "Date Of Birth:   " . $row["dob"] . "<br>";
            echo "<h3> GRE Score </h3>";
            echo "GRE Total:   " . $row["gretotal"] . "<br>";
            echo "GRE Analaytical:   " . $row["greanalytical"] . "<br>";
            echo "GRE Quantitive:   " . $row["grequantitive"] . "<br>";
            echo "GRE Date:   " . $row["gredate"] . "<br>";
            echo "GRE Advanced Score:   " . $row["greadvscore"] . "<br>";
            echo "GRE Subject:   " . $row["gresubj"] . "<br>";
            echo "<h3> TOEFL Score </h3>";
            echo "TOEFL Score:   " . $row["toeflscore"] . "<br>";
            echo "TOEFL Date:   " . $row["toefldate"] . "<br>";
            echo "<h3> Bachelor's degree </h3>";
            echo "Bachelor's Degree GPA  " . $row["bachgpa"] . "<br>";
            echo "Bachelor's Major:   " . $row["bachmajor"] . "<br>";
            echo "Bachelor's Year:   " . $row["bachyear"] . "<br>";
            echo "Bachelor's University:   " . $row["bachuni"] . "<br>";
            echo "<h3> Master's Degree </h3>";
            echo "Master's Degree GPA:   " . $row["masgpa"] . "<br>";
            echo "Master's Major:   " . $row["masmajor"] . "<br>";
            echo "Master's Year:   " . $row["masyear"] . "<br>";
            echo "Master's University:   " . $row["masuni"] . "<br>";
            echo "<br>";
            echo "Area of Interest:   " . $row["areaofint"] . "<br>";
            echo "Experience:   " . $row["experience"] . "<br>";
            echo "<h3>Recommendation Letters: </h3>";
            
            //check to see if there are any recommendation letter submitted and if they have not been rated yet
            $checklettersquery = "SELECT * FROM recommendation WHERE applicationid='" . $_SESSION['currentid'] . "' ";
            $checkresult       = mysqli_query($connection, $checklettersquery);
            if (mysqli_num_rows($checkresult) == 0) {
                echo "There are no recommendation letters submitted yet.";
            } else {
                $query3   = "SELECT * FROM recommendation WHERE rating IS NULL AND applicationid='" . $_SESSION['currentid'] . "'";
                $result_3 = mysqli_query($connection, $query3);
                if (mysqli_num_rows($result_3) > 0) {
                    echo "Please review available recommendation letters: ";
                    echo "<br><ul>";
                    while ($row = mysqli_fetch_assoc($result_3)) {
                        $reviewname = $row['recommendationid'];
                        if (is_null($row['rating'])) {
                            echo '<li><a href = "review-form.php?ReviewRecommendation=' . $row['recommendationid'] . '">Letter</a></li>';
                        }
                    }
					echo "</ul>";
                } else {
                    echo "All letters have been rated<br/>";
                }
            }
            //Review option if there are no unrated letters and no previous review posted
            echo '<h3>Submit Decisions:</h3>';
            $query4   = "SELECT * FROM recommendation WHERE applicationid='" . $_SESSION['currentid'] . "' AND rating IS NULL";
            $result_4 = mysqli_query($connection, $query4);
            if (mysqli_num_rows($result_4) == 0) {
                $checkreviewquery = "SELECT * FROM review WHERE applicationid ='" . $_SESSION['currentid'] . "'";
                $checkreview      = mysqli_query($connection, $checkreviewquery);
                if (mysqli_num_rows($checkreview) == 0) {
                    //the start button appears as soon as all the letters are rated and no previous review has been posted
                    echo '<form><input type="submit" value="Start" name="Start" ></form>';
                } else {
                    echo "<br>";
                    echo "Review already submitted";
                }
            } else {
                echo "Recommendation letters must be rated first";
            }
        }
    }
}
//for each unrated recommendation letter, options and textfields available to the reviewer to rate the letters
if (isset($_GET['ReviewRecommendation'])) {
    $_SESSION['recommendationid'] = $_GET['ReviewRecommendation'];
    $query3                       = "SELECT * FROM recommendation WHERE recommendationid ='" . $_SESSION['recommendationid'] . "'";
    $result_3                     = mysqli_query($connection, $query3);
    if (mysqli_num_rows($result_3) > 0) {
        while ($row = mysqli_fetch_assoc($result_3)) {
            echo "Recommendation ID:   " . $row["recommendationid"] . "<br>";
            echo "Writer Name:   " . $row["writername"] . "<br>";
            echo "Writer Email:   " . $row["writeremail"] . "<br>";
            echo "Affiliation:   " . $row["affiliation"] . "<br>";
			echo "<br /><form method='POST' id='downloadform'>
			<input type='submit' name='download' value='Download File'>
			</form>";
            echo '<h4>Ratings (Worst = 1, Best = 5):</h4>';
  //geting overal, generic, and credible rating. ALL required 
            echo ' <form action="" method="get">
Overall Rating: 
<select name="myvalue" required>
  <option value="" selected="selected"></option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3"> 3</option>
    <option value="4"> 4</option>
  <option value="5"> 5</option>
</select>

<br/>

Generic Rating: 
<select name="generic" required>
    <option value="" selected="selected"></option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3"> 3</option>
    <option value="4"> 4</option>
  <option value="5"> 5</option>
</select>
 
<br/>

Credible Rating: 
<select name="credible" required >
    <option value="" selected="selected"></option>
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3"> 3</option>
    <option value="4"> 4</option>
  <option value="5"> 5</option>
</select>

<br/><br/>
<input type="submit" value="Submit Ratings" name="SubmitReview" >
</form>';
            
        }
    }
}

// Download recommendation:
if (isset($_POST['download'])) {
	$query = "SELECT letterfile
		FROM recommendation
		WHERE recommendationid = {$_SESSION['recommendationid']}";
	$result = mysqli_query($connection, $query);
	$filedata = mysqli_fetch_array($result)["letterfile"];
	$length = strlen($filedata);
	if ($length > 0) {
		header("Content-Type: application/pdf");
		header("Content-Length: " . strlen($filedata));
		header("Content-Disposition: attachment; filename='" . $_SESSION['recommendationid'] . ".pdf'");
		ob_clean();
		flush();
		echo $filedata;
	} else {
		echo "<script type='text/javascript'>alert('File not available');</script>";
	}
}

//updating queries based on ratings on recommendation letters 
if (isset($_GET['SubmitReview'])) {
    $rat = $_GET['myvalue'];
    //Updating rating
    $sql = " UPDATE recommendation SET rating = $rat WHERE recommendationid = '" . $_SESSION['recommendationid'] . "'";
    if (mysqli_query($connection, $sql)) {
        echo " ";
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
    //updating Generic rating
    $Generic  = $_GET['generic'];
    $Credible = $_GET['credible'];
    
    
    $sql = " UPDATE recommendation SET genericrating = $Generic WHERE recommendationid = '" . $_SESSION['recommendationid'] . "'";
    if (mysqli_query($connection, $sql)) {
        echo " ";
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
    //updating Credible rating
    $sql = " UPDATE recommendation SET crediblerating = $Credible WHERE recommendationid = '" . $_SESSION['recommendationid'] . "'";
    if (mysqli_query($connection, $sql)) {
        echo " ";
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
    
    echo "Recommendation letter ratings submitted";
    echo "</br>";
    //going back to main review form
    echo "<a href=\"review-form.php?review=" . $_SESSION['currentid'] . "\">Back to Review Form</a>";
}
//starts posting Decisions
if (isset($_GET['Start'])) {
    echo '<form action="" method="get" >
<select name="Decision" required>
<option value="" selected="selected"></option>
<option value="4">Admit With Aid</option>
<option  value="3">Admit without Aid</option>
<option  value="2">Borderline Admit</option>
<option  value="1">Reject</option>
<input name="Next" type="submit" />
</form>';
}
//different results based on the decision
if (isset($_GET['Next'])) {
    
    $_SESSION['decision'] = (int) $_GET['Decision'];
    
    //if rejection, provide reasons and comments
    if ($_GET['Decision'] == 1) {
        echo " Reasons for Rejection";
        $Decision = $_GET['Decision'];
        
        echo "</br>";
        echo "</br>";
        echo '<form form action="" method="get">
<select name="Decision2" required >
    <option value="" selected="selected"></option>
<option value="A">Incomplete Record</option>
<option value="B">Does not meet minimum requirements</option>
<option value="C">Unspecified Area of Interest</option>
<option value="D">Problems with Recommendation Letters</option>
<option value="E">Not competitive enough</option>
<option value="F">Other reason</option>
</select></br>
Comments:<textarea name="Comments" rows="3" cols="16" maxlength = "100"/></textarea>
<input name="Submit" type="submit" /></br>
</form>';
    }
    // if acceptance, provide deficiency courses and comments
    if ($_GET['Decision'] == 4 || $_GET['Decision'] == 2 || $_GET['Decision'] == 3) {
        echo '<form action="" method="get">
Deficiency Course: <textarea name="DefCourse" rows="3" cols="16" maxlength = "100"></textarea></br>
Comments:<textarea name="Comments" rows="3" cols="16" maxlength = "100"/></textarea>
<input name="Submit2" type="submit" />
</form>';
    }
}
//}
//updating queries with reviews
if (isset($_GET['Submit'])) {
    $Decision2 = $_GET['Decision2'];
    $Comments  = $_GET['Comments'];
    
    $sql = " INSERT INTO review(decision, applicationid, defcourse, comments, reasons) VALUES('" . $_SESSION['decision'] . "', '" . $_SESSION['currentid'] . "', '$DefCourse', '$Comments', '$Decision2')";
    if (mysqli_query($connection, $sql)) {
        echo " ";
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
    echo "Review submitted. Thank you!";
}
if (isset($_GET['Submit2'])) {
    $DefCourse = $_GET['DefCourse'];
    $Comments  = $_GET['Comments'];
    $sql       = " INSERT INTO review(decision, applicationid, defcourse, comments) VALUES('" . $_SESSION['decision'] . "', '" . $_SESSION['currentid'] . "', '$DefCourse', '$Comments')";
    if (mysqli_query($connection, $sql)) {
        echo " ";
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
    echo "Review submitted. Thank you!";
}
?>

</body>
</html>
