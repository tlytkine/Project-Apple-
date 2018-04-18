<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<style>

	#bannerimage {
  width: 100%;
  background-image: url(1.jpg);
  height: 190px;
  background-color: #268FD0;
  background-position: center;
  background-repeat: no-repeat;
}
	body {

	}
	</style>

<body>
<?php
session_start();

// Set home page based on user type:
$home_pages = array(
	"Applicant" => "applicant-home.php",
	"System Administrator" => "admin-home.php",
	"Grad Secretary" => "gs-home.php",
	"Faculty Reviewer" => "reviewer-home.php",
	"CAC" => "cac-home.php",
);
$home_page = $home_pages[$_SESSION["type"]];

// Redirect to user home page if accessing restricted page:
if (!in_array($_SESSION["type"], $allowed_user_types)) {
	if (isset($home_page)) {
		header("location: {$home_page}");
	} else {
		header("location: login.php");
	}
}

?>
</body>
<head>

<!--<div id="bannerimage">-->
	
Email: <?php echo $_SESSION["email"]; ?>
<?php echo " <a href=\"{$home_page}\">[Menu]</a> " ?>
<a href="logout.php">[Logout]</a><br/>

</div>

</head>

</html>