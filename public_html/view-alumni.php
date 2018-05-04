<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>Update Classes</title>
    <link rel="stylesheet" href="style.css">
</head>


<?php
    $allowed_user_types = array(
        "ADMIN",
        "GS"
    );
    include 'header.php';
    include 'db-connect.php';

    /* used for page display */
    $year_search = $_POST["year_search"];
    $degree_search = $_POST["degree_search"];

    /* display for search by year */
	if($year_search){
        /* get current year for error checking */
        $query = "SELECT c.year
                    FROM courses c
                    LIMIT 1;";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $current_year = $row["year"];

		$year = $_POST["year"];

        $query = "SELECT a.firstname, a.lastname, u.email
                  FROM alumnipersonalinfo a, users u
                  WHERE a.id = u.id AND a.graduationyear = '$year';";

        $result = mysqli_query($connection, $query);

        /* display alumni */
        if (mysqli_num_rows($result) > 0){
            echo "<table>";
            echo "<tr><th colspan=2>Name</th><th>Email</th></tr>";

            while ($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row["firstname"]."</td>";
                echo "<td>".$row["lastname"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        else if(is_numeric($year) && $year <= $current_year){
            echo "No alumni for that year";
        }
        else{
            echo "Invalid year";
        }
	}
    else if($degree_search){
        $degree = $_POST["degree_name"];

        $query = "SELECT a.firstname, a.lastname, u.email
                  FROM alumnipersonalinfo a, users u
                  WHERE a.id = u.id AND a.degreename = '$degree';";

        $result = mysqli_query($connection, $query);

        /* display alumni */
        if (mysqli_num_rows($result) > 0){
            echo "<table>";
            echo "<tr><th colspan=2>Name</th><th>Email</th></tr>";

            while ($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row["firstname"]."</td>";
                echo "<td>".$row["lastname"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        else{
            echo "No alumni for that degree";
        }
    }
    else{
		echo '<form method="post" action="view-alumni.php">';

        echo '<h4>Search by Graduation Year (and semester?)</h4>';
		//echo "<input type='radio' name='semester' value='fall'>Fall";
		//echo "<input type='radio' name='semester' value='spring'>Spring <br/>";
        echo '<input type="text" name="year"><br>';
        echo '<input type="submit" name="year_search" value="Search">';

        echo '<h4>Search by Degree</h4>';
        echo '<select name="degreename">';
        echo '<option value="MS_CS">MS_CS</option>';
        echo '</select>';
		echo '<input type="submit" name="degree_search" value="Search">';

        echo '</form>';
	}
?>

</body>
</html>
