<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>View Transcript</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
$allowed_user_types = array(
    "ALUMNI",
    "STUDENT"
);
include 'header.php';

?>
<h2>Transcript</h2>

<?php
    include 'db-connect.php';

    /* extract user */
	$user = $_SESSION["email"];

    /* get and display student name */
    $query = "SELECT p.firstname, p.lastname
                FROM personalinfo p, users u
                WHERE p.id = u.id AND u.email = '".$user."';";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    echo "<h2>".$row["firstname"]." ".$row["lastname"],"</h2>";

    /* get transcript */
    $query = "SELECT t.dept, t.coursenum, c.credithours, t.grade, t.year, t.semester, t.title
        FROM transcripts t, courses c, users u
        WHERE t.coursenum = c.coursenum AND t.dept = c.dept AND
        t.studentid = u.id AND u.email = '$user'
        ORDER BY t.year, t.semester DESC;";

    $result = mysqli_query($connection, $query);

    $total_credits = 0;
    $weight = 0;
    $sum = 0;

    /* display transcript */
    if (mysqli_num_rows($result) > 0){
        echo "<table>";
        $cur_year = ""; //track current year
        $cur_sem = ""; //track current semester

        while ($row = mysqli_fetch_assoc($result)){
            if($cur_year != $row["year"] || $cur_sem != $row["semester"]){
                echo "</table><br><table>";
                echo "<tr><th colspan=2>Course</th><th>Title</th><th>Credits</th><th>Grade</th><th>Semester</th><th>Year</th></tr>";
                $cur_year = $row["year"];
                $cur_sem = $row["semester"];
            }

            echo "<tr>";

            echo "<td>".$row["dept"]."</td>";
            echo "<td>".$row["coursenum"]."</td>";
            echo "<td>".$row["title"]."</td>";
            echo "<td>".$row["credithours"]."</td>";
            echo "<td>".$row["grade"]."</td>";
            echo "<td>".$row["semester"]."</td>";
            echo "<td>".$row["year"]."</td>";

            echo "</tr>";

            /* gpa calculation */
            $weight = $row["credithours"];
            if (strcmp($row["grade"], "IP") != 0) {
                $total_credits = $total_credits + $weight;
            }

            if (strcmp($row["grade"], "A") == 0) {
                $sum = $sum + ($weight * 4.0);
            } else if (strcmp($row["grade"], "A-") == 0) {
                $sum = $sum + ($weight * 3.7);
            } else if (strcmp($row["grade"], "B+") == 0) {
                $sum = $sum + ($weight * 3.3);
            } else if (strcmp($row["grade"], "B") == 0) {
                $sum = $sum + ($weight * 3.0);
            } else if (strcmp($row["grade"], "B-") == 0) {
                $sum = $sum + ($weight * 2.7);
            } else if (strcmp($row["grade"], "C+") == 0) {
                $sum = $sum + ($weight * 2.3);
            } else if (strcmp($row["grade"], "C") == 0) {
                $sum = $sum + ($weight * 2.0);
            } else if (strcmp($row["grade"], "F") == 0) {
                $sum = $sum + ($weight * 0.0);
            }
        }

        echo "</table>";

        $gpa = $sum / $total_credits;

        echo "<br/>";
        echo "<br/>";
        echo "<br/>";
        echo "<h4> GPA: " . $gpa;
    }
    else{
        echo "You not taken any classes yet";
    }

?>

</body>
</html>
