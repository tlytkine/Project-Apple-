<html>
<head>
<meta charset="UTF-8">
    <title>View Application</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array(
    "CAC",
    "Grad Secretary",
    "System Administrator"
);
include 'header.php';
?>
<h3>View Application</h3>

<?php
include 'db-connect.php';

if (isset($_GET['viewapplication'])) {
    $_SESSION['currentappid'] = $_GET['viewapplication'];
    //query showing current selected applicaion
    $query = "SELECT * FROM application, academicinfo, personalinfo  WHERE application.id=academicinfo.applicationid AND application.id=personalinfo.applicationid AND application.id='" . $_SESSION['currentappid'] . "'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
 
          echo "Applying for Degree: ".$row['degreeapplyingfor']."<br>";
          echo "Semester and Year: ". $row["semester"]."";
          echo "  ".$row["year"]."<br>";
          echo "<h3> Personal Information </h3>";
            
            echo "Application Id:   " . $row["applicationid"] . "<br>";
            echo "First Name:   " . $row["firstname"] . "<br>";
            echo "Last Name:   " . $row["lastname"] . "<br>";
            echo "Date Of Birth:   " . $row["dob"] . "<br>";
            echo "<h3> GRE Score </h3>";
            echo "GRE Total:   " . $row["gretotal"] . "<br>";
            echo "GRE Analaytical:   " . $row["greanalytical"] . "<br>";
            echo "GRE Quantitive:   " . $row["grequantitive"] . "<br>";
            echo "GRE date:   " . $row["gredate"] . "<br>";
            echo "GRE advanced Score:   " . $row["greadvscore"] . "<br>";
            echo "GRE subject:   " . $row["gresubj"] . "<br>";
            echo "<h3> TOEFL Score </h3>";
            echo "TOEFL score:   " . $row["toeflscore"] . "<br>";
            echo "TOEFL date:   " . $row["toefldate"] . "<br>";
            echo "<h3> Bachelor's degree </h3>";
            echo "Bachelor's Degree GPA  " . $row["bachgpa"] . "<br>";
            echo "Bachelor's Major:   " . $row["bachmajor"] . "<br>";
            echo "Bachelor's year:   " . $row["bachyear"] . "<br>";
            echo "Bachelor's university:   " . $row["bachuni"] . "<br>";
            echo "<h3> Master's Degree </h3>";
            echo "Master's Degree GPA:   " . $row["masgpa"] . "<br>";
            echo "Master's Major:   " . $row["masmajor"] . "<br>";
            echo "Master's year:   " . $row["masyear"] . "<br>";
            echo "Master's university:   " . $row["masuni"] . "<br>";
            echo "Area of Interest:   " . $row["areaofint"] . "<br>";
            echo "Experience:   " . $row["experience"] . "<br>";
        }
    }
    //recommendation letters submitted for the application
    echo "<h3>Recommendation Letters ratings: </h3>";
    $checklettersquery = "SELECT * FROM recommendation WHERE applicationid='" . $_SESSION['currentappid'] . "' ";
    $checkresult       = mysqli_query($connection, $checklettersquery);
    
    if (mysqli_num_rows($checkresult) > 0) {
        while ($row = mysqli_fetch_assoc($checkresult)) {
            echo "Recommendation ID: " . $row['recommendationid'] . "<br>";
            echo "From: " . $row['writername'] . "<br>";
            echo "Writer's Email: " . $row['writeremail'] . "<br>";
            echo "Affiliation: " . $row['affiliation'] . "<br>";
            if ($row['rating'] == NULL) {
                echo "No rating has been submitted yet<br>";
            } else {
                echo "Rating : " . $row['rating'] . "<br>";
            }
                echo "Generic Rating : " . $row['genericrating'] . "<br>";
                echo "Credible Rating : " . $row['crediblerating'] . "<br>";


            echo "<hr>";
        }
    } else {
        echo "no rating has been submitted for this letter";
    }

}


?>
</body>
</html>