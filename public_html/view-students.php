<html>
<body>
<head>
    <meta charset="UTF-8">
    <title>View Students</title>
    <link rel="stylesheet" href="style.css">
</head>

<?php
    $allowed_user_types = array(
        "ADMIN",
        "GS"
    );
    include 'header.php';
    include 'db-connect.php';

    echo "<h1>View Students</h1>";

    /* used for page display */
    $year_search = $_POST["year_search"];
    $degree_search = $_POST["degree_search"];

    /* display for search by year */
	if($year_search){
        $year = $_POST["year"];

        $query = "SELECT p.id, p.firstname, p.lastname, p.dob, p.address, u.email
                  FROM personalinfo p, users u, roles r, advises a
                  WHERE a.studentid = p.id AND p.id = u.id AND p.id = r.id AND r.role = 'STUDENT' AND a.admityear = '$year';";

        $result = mysqli_query($connection, $query);

        /* display alumni */
        if (mysqli_num_rows($result) > 0){
            echo "<table>";
            echo "<tr><th>ID</th><th colspan=2>Name</th><th>Birthday</th><th>Address</th><th>Email</th></tr>";

            while ($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["firstname"]."</td>";
                echo "<td>".$row["lastname"]."</td>";
                echo "<td>".$row["dob"]."</td>";
                echo "<td>".$row["address"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        else if(!is_numeric($year)){
            echo "Please enter a valid year";
        }
        else{
            echo "No students";
        }
	}
    else if($degree_search){
        $degreename = $_POST["degreename"];
        $query = "SELECT p.id, p.firstname, p.lastname, p.dob, p.address, u.email, a.degreename
                  FROM personalinfo p, users u, roles r, advises a
                  WHERE p.id = a.studentid AND p.id = u.id AND p.id = r.id AND r.role = 'STUDENT' AND a.degreename = '$degreename';";

        $result = mysqli_query($connection, $query);

        /* display alumni */
        if (mysqli_num_rows($result) > 0){
            echo "<table>";
            echo "<tr><th>ID</th><th colspan=2>Name</th><th>Birthday</th><th>Address</th><th>Email</th><th>Degree</th></tr>";

            while ($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["firstname"]."</td>";
                echo "<td>".$row["lastname"]."</td>";
                echo "<td>".$row["dob"]."</td>";
                echo "<td>".$row["address"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "<td>".$row["degreename"]."</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        else{
            echo "No students";
        }
    }
    else{
		echo '<form method="post" action="view-students.php">';

        echo '<h4>View by Admit Year</h4>';
        echo '<input type="text" name="year"><br>';
        echo '<input type="submit" name="year_search" value="Search">';

        echo '<h4>View by Degree</h4>';
        echo '<select name="degreename">';
        echo '<option value="MS">MS</option>';
        echo '<option value="PhD">PhD</option>';
        echo '<option value="Direct PhD">Direct PhD</option>';
        echo '</select>';
		echo '<input type="submit" name="degree_search" value="Search">';

        echo '</form>';
	}

?>

</body>
</html>
