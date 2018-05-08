<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
session_start();

// Redirect to home page if accessing restricted page:
if (isset($_SESSION["roles"])) {
	if (count(array_intersect($_SESSION["roles"], $allowed_user_types)) == 0) {
		header("location: menu.php");
	}
} else {
	header("location: login.php");
}

?>

<div id="head">
<div id="logo">
	<span class="imgspan"></span>
	<img src="https://creativeservices.gwu.edu/sites/g/files/zaxdzs1101/f/image/gw_txt_4cp_pos_0.png" height="80%">
</div>
<div id="nav">
	<a href="menu.php">Menu</a> 
	<a href="logout.php">Logout</a>
</div>
<div id="email">
<b>Email:</b> <?php echo $_SESSION["email"]; ?><br/>
</div>
</div>
<div id="content">
</body>

</html>
