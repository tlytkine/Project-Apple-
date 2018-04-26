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
	
$query = "SELECT applicationsubmitted FROM documentstatus WHERE applicationid = $id";
$result = mysqli_query($connection, $query);
$row = mysqli_fetch_array($result);
$submitted = $row['applicationsubmitted'];

  echo "<h1>Application</h1>";
  
  if ($submitted) {
	echo "<h3>Application Submitted</h3>";
  }
  
  echo "<form method=\"POST\" action=\"process-application.php\">";
  
  if ($submitted) {
	  echo "<fieldset disabled>";
  }
  $query = "SELECT * FROM admissionsapplication WHERE id = '$id'";
  $result = mysqli_query($connection, $query);

  while ($row = mysqli_fetch_array($result)) {
    echo getSemesterAndYearApplyingFor($row);
  }
 
  $query = "SELECT * FROM academicinfo WHERE applicationid = $id";
  $result = mysqli_query($connection, $query);

  while ($row = mysqli_fetch_array($result)) {

    echo getApplyingForDegree($row);

    echo getApplicantCredentials($row);

    echo greSubj($row);

    getMoreTestInfo($row);

    getPriorDegrees($row);

    getSupplementalInfo($row);

  }

  $query = "SELECT * FROM recommendation WHERE applicationid = $id LIMIT 3";
  $result = mysqli_query($connection, $query);
  
  $rec_number = 1;

  while ($row = mysqli_fetch_array($result)) {

    getLettersOfRec($row, $rec_number);
	$rec_number++;

  }

  submitData();

  function getSemesterAndYearApplyingFor($row){

    echo "<h2>Academic Information: </h2>";
    $semester = $row["semester"];
    $selected0 = ($semester == " ") ? "selected" : "";
    $selected1 = ($semester == "Fall") ? "selected" : "";
    $selected3 = ($semester == "Spring") ? "selected" : "";

    echo "<label for=\"semester\">Semester Applying For: </label>";

    echo "<select name=\"semester\" required>
    
      <option value=\"\" $selected0></option>
      <option value=\"Fall\" $selected1>Fall</option>
      <option value=\"Spring\" $selected3>Spring</option>
      </select>";

    echo "<br />";


      $year=date("Y");

    echo "<label for=\"yearapp\">Year:</label>";
 echo "<select name=\"yearapp\" required>
      <option value=\"\" $selected0></option>
      <option value=\"$year\" $selected1>$year</option>
 </select>";

echo "<br>";

  }

  function getApplyingForDegree($row) {
    $degree = $row["degreeapplyingfor"];
    $selected0 = ($degree == " ") ? "selected" : "";
    $selected1 = ($degree == "M.S.") ? "selected" : "";
    $selected3 = ($degree == "Ph.D.") ? "selected" : "";
    $selected4 = ($degree == "Direct Ph.D.") ? "selected" : "";

    echo "<label for=\"degree\">Applying for Degree: </label>";

    echo "<select name=\"degree\" required>
      <option value=\"\" $selected0></option>
      <option value=\"M.S.\" $selected1>M.S.</option>
      <option value=\"Ph.D.\" $selected3>Ph.D.</option>
      <option value=\"Direct Ph.D.\" $selected4>Direct Ph.D.</option>       
      </select>";

    echo "<br />"; 




  }

  function getApplicantCredentials($row) {

    echo "<br />";
    echo "<h4>Test Scores:</h4>";
    echo "<h5>GRE:</h5>";
  
    echo "<label for=\"total\">Total: </label>";
    echo "<input type=\"number\" id=\"total\" name=\"total\" min=\"0\" max=\"340\" step=\"10\" value= \"{$row["gretotal"]}\" /><br />";

    echo "<label for=\"verbal\">Verbal: </label>";
    echo "<input type=\"number\" id=\"verbal\" name=\"verbal\" min=\"130\" max=\"170\" step=\"1\" value= \"{$row["greverbal"]}\" /><br />";

    echo "<label for=\"analytical\">Analytical: </label>";
    echo "<input type=\"number\" id=\"analytical\" name=\"analytical\" min=\"0\" max=\"6\" step=\"0.5\" value= \"{$row["greanalytical"]}\" /><br />";

    echo "<label for=\"quantitative\">Quantitative: </label>";
    echo "<input type=\"number\" id=\"quantitative\" name=\"quantitative\" min=\"130\" max=\"170\" step=\"1\" value= \"{$row["grequantitive"]}\" /><br />";

    echo "<label for=\"gredate\">Date: </label>";
    echo "<input type=\"date\" id=\"gredate\" name=\"gredate\" value= \"{$row["gredate"]}\" /><br />";

    echo "<h5>GRE Advanced:</h5>";

    echo "<label for=\"greadvancedscore\">Score: </label>";
    echo "<input type=\"number\" id=\"greadvancedscore\" name=\"greadvancedscore\" min=\"0\" max=\"1000\" step=\"10\" value= \"{$row["greadvscore"]}\"><br />";



  }

  
  function greSubj($row) {
      $gresubj = $row["gresubj"];
      $selected0 = ($gresubj == "none") ? "selected" : "";
      $selected1 = ($gresubj == "Biology") ? "selected" : "";
      $selected3 = ($gresubj == "Chemistry") ? "selected" : "";
      $selected4 = ($gresubj == "Literature In English") ? "selected" : "";
      $selected5 = ($gresubj == "Mathematics") ? "selected" : "";
      $selected6 = ($gresubj == "Physics") ? "selected" : "";
      $selected7 = ($gresubj == "Psychology") ? "selected" : "";

      echo "<label for=\"subject\">Subject: </label>";

      echo "<select name=\"subject\">
        <option value=\"none\" $selected0></option>
        <option value=\"Biology\" $selected1>Biology</option>
        <option value=\"Chemistry\" $selected3>Chemistry</option>
        <option value=\"Literature In English\" $selected4>Literature in English</option>
        <option value=\"Mathematics\" $selected5>Mathematics</option>
        <option value=\"Physics\" $selected6>Physics</option>
        <option value=\"Psychology\" $selected7>Psychology </option>        
      </select>";
  }

  function getMoreTestInfo($row) {
    echo "<br />";
    echo "<label for=\"greadvanceddate\">Date: </label>";
    echo "<input type=\"date\" id=\"greadvanceddate\" name=\"greadvanceddate\" value= \"{$row["greadvdate"]}\" ><br />";

    echo "<h5>TOEFL:</h5>"; 

    echo "<label for=\"toeflscore\">Score: </label>";
    echo "<input type=\"number\" id=\"toeflscore\" name=\"toeflscore\" min=\"0\" max=\"120\" step=\"1\" value= \"{$row["toeflscore"]}\"><br />";

    echo "<label for=\"toefldate\">Date: </label>";
    echo "<input type=\"date\" id=\"toefldate\" name=\"toefldate\" value= \"{$row["toefldate"]}\"><br />";

  }

  function getPriorDegrees($row) {
    echo "<h4>Prior Degrees:</h4>"; 

    echo "<h4>Bachelors Degree:</h4>";

    echo "<label for=\"gpa\">GPA: </label>";
    echo "<input type=\"number\" id=\"gpa\" name=\"gpa\" min=\"0\" max=\"4\" step=\"0.01\" value= \"{$row["bachgpa"]}\" required><br />";

    echo "<label for=\"major\">Major: </label>";
    echo "<input type=\"text\" id=\"major\" name=\"major\" value= \"{$row["bachmajor"]}\" maxlength=\"30\"required><br />";

    echo "<label for=\"year\">Year: </label>";
    echo "<input type=\"number\" id=\"year\" name=\"year\" min=\"1900\" max=\"3000\" step=\"1\" value= \"{$row["bachyear"]}\" required><br />";

    echo "<label for=\"university\">University: </label>";
    echo "<input type=\"text\" id=\"university\" name=\"university\" value= \"{$row["bachuni"]}\" maxlength=\"30\" required><br />";

    echo "<h4>Masters Degree:</h4>";

    echo "<label for=\"gpa2\">GPA: </label>";
    echo "<input type=\"number\" id=\"gpa2\" name=\"gpa2\" min=\"0\" max=\"4\" step=\"0.01\" value= \"{$row["masgpa"]}\"><br />";

    echo "<label for=\"major2\">Major: </label>";
    echo "<input type=\"text\" id=\"major2\" name=\"major2\" value= \"{$row["masmajor"]}\" maxlength=\"30\"><br />";

    echo "<label for=\"year2\">Year: </label>";

    echo "<input type=\"number\" id=\"year2\" name=\"year2\" min=\"1900\" max=\"3000\" step=\"1\" value= \"{$row["masyear"]}\"><br />";

    echo "<label for=\"university2\">University: </label>";
    echo "<input type=\"text\" id=\"university2\" name=\"university2\" value= \"{$row["masuni"]}\" maxlength=\"30\"><br />";

  }

  function getSupplementalInfo($row) {
    echo "<h2>Supplemental Information:</h2>";

    echo "<label for=\"areaofinterest\">Areas of Interest: </label>  <br>";
    echo "<textarea id=\"areaofinterest\" name=\"areaofinterest\" maxlength=\"300\"/>".$row["areaofint"]."</textarea><br />";

    echo "<label for=\"experience\">Experience: </label>  <br>"; 
    echo "<textarea id=\"experience\" name=\"experience\" maxlength=\"300\"/>".$row["experience"]."</textarea><br />";

    echo "<h2>Letters of Recommendation Information:</h2>";


  }

  function getLettersOfRec($row, $rec_number) {
    echo "<h4>Recommender:</h4>"; 

    echo "<label for=\"writername\">Recommender Name: </label>";
    echo "<input type=\"text\" id=\"writername{$rec_number}\" name=\"writername{$rec_number}\" value= \"{$row["writername"]}\" maxlength=\"30\" required ><br />";

    echo "<label for=\"writeremail\">Recommender Email: </label>";
    echo "<input type=\"email\" id=\"writeremail{$rec_number}\" name=\"writeremail{$rec_number}\" value= \"{$row["writeremail"]}\" required><br />";

    echo "<label for=\"affiliation\">Affiliation to Applicant: </label>";
    echo "<input type=\"text\" id=\"affiliation{$rec_number}\" name=\"affiliation{$rec_number}\" value= \"{$row["affiliation"]}\" maxlength=\"30\" required><br />";



  }

  function submitData(){
    echo "<br />"; 
    echo "<br />"; 

    echo "<input type=\"submit\" value=\"Save Progress \" name=\"save\" />";
    echo "<input type=\"submit\" value=\"Submit \" name=\"submit\" />";
  }

  if ($submitted) {
	  echo "</fieldset>";
  }
  echo "</form>";



?>

</body>
</html>