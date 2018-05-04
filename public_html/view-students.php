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
    $year = $_POST["year"];
    $degree = $_POST["degree"];

    /* display for search by year */
	if($year){
        $query = "SELECT p.id, p.firstname, p.lastname, p.dob, p.address, u.email
                  FROM personalinfo p, users u, roles r, advises a
                  WHERE a.studentid = p.id AND p.id = u.id AND p.id = r.id AND r.role = 'STUDENT'
                  ORDER BY a.admityear DESC;";

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
        else{
            echo "No students";
        }
	}
    else if($degree){
        $query = "SELECT p.id, p.firstname, p.lastname, p.dob, p.address, u.email, a.degreename
                  FROM personalinfo p, users u, roles r, advises a
                  WHERE p.id = a.studentid AND p.id = u.id AND p.id = r.id AND r.role = 'STUDENT'
                  ORDER BY a.degreename;";

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
        echo '<input type="submit" name="year" value="Go">';

        echo '<h4>View by Degree</h4>';
		echo '<input type="submit" name="degree" value="Go">';

        echo '</form>';
	}

?>

</body>
</html>
