<html lang="en-US">
<head>
  <meta charset="UTF-8">
  <title>Applicant Home</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
<?php
$allowed_user_types = array("Applicant");
include 'header.php';

include 'db-connect.php';
    $id = $_SESSION['id'];

  echo "<h1>Personal Information </h1>";
  echo "<form method=\"POST\" action=\"process-personal-info.php\">";

 
  $query = "SELECT * FROM personalinfo WHERE applicationid = $id";
  $result = mysqli_query($connection, $query);
  while ($row = mysqli_fetch_array($result)) {
    $appid = $row["applicationid"]; 
  }

    if(isset($appid)){
        $query = "SELECT * FROM personalinfo WHERE applicationid = $id";
        $result = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_array($result)) {
          getGeneralInformationAfterSubmit($row);
        }    
    }
    else{
          echo getGeneralInformation($row);
          echo submitData();
    }
 
  function getGeneralInformation($row) {
      echo "<label for=\"firstname\">First name: </label>";
      echo "<input type=\"text\" id=\"firstname\" name=\"firstname\" maxlength=\"30\" required /><br />";

      echo "<label for=\"lastname\">Last name: </label>";
      echo "<input type=\"text\" id=\"lastname\" name=\"lastname\" maxlength=\"30\"required /><br />";

      echo "<label for=\"dob\">Date of Birth: </label>";
      echo "<input type=\"date\" id=\"dob\" name=\"dob\" min=\"1000-01-01\" max=\"3000-12-31\" required /><br />";

      echo "<label for=\"ssn\">Social Security Number (###-##-####): </label>";
      echo "<input id=\"ssn\" name=\"ssn\" pattern=\"^\d{3}-\d{2}-\d{4}$\" required/><br />";


      echo "<label for=\"address\">Address: </label>";
      echo "<input type=\"text\" id=\"address\" name=\"address\" maxlength=\"100\" required /><br />";


  }

  function getGeneralInformationAfterSubmit($row) {
      echo "<label for=\"firstname\">First name: </label>";
      echo "<input type=\"text\" id=\"firstname\" name=\"firstname\" value= \"{$row["firstname"]}\" required disabled/><br />";

      echo "<label for=\"lastname\">Last name: </label>";
      echo "<input type=\"text\" id=\"lastname\" name=\"lastname\" value= \"{$row["lastname"]}\" required disabled/><br />";

      echo "<label for=\"dob\">Date of Birth: </label>";
      echo "<input type=\"date\" id=\"dob\" name=\"dob\" value= \"{$row["dob"]}\" required disabled/><br />";

      echo "<label for=\"ssn\">Social Security Number: </label>";
      echo "<input type=\"text\" id=\"ssn\" name=\"ssn\" value= \"{$row["ssn"]}\" required disabled/><br />";

      echo "<label for=\"address\">Address: </label>";
      echo "<input type=\"text\" id=\"address\" name=\"address\" value= \"{$row["address"]}\" required disabled/><br />";


  }


  function submitData(){
    echo "<br />"; 
    echo "<br />"; 
    echo "<input type=\"submit\" value=\"Submit \" name=\"submit\" />";
  }




  echo "</form>";



?>

</body>
</html>