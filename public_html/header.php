<html lang="en-US">
<head>
	<meta charset="UTF-8">
<body>
<?php
session_start();

// Redirect to home page if accessing restricted page:
/*
if (!in_array($_SESSION["roles"], $allowed_user_types)) {
	if (isset($_SESSION["roles"])) {
		header("location: menu.php");
	} else {
		header("location: login.php");
	}
}
*/

?>
</body>
<head>
	
Email: <?php echo $_SESSION["email"]; ?> 
<a href="menu.php">[Menu]</a> 
<a href="logout.php">[Logout]</a><br/>

</div>

</head>

</html>