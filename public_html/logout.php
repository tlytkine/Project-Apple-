<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Logout</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
<?php include 'header-public.php'; ?>

<?php
session_start();
session_destroy();

echo "Logged out successfully<br/><br/><a href=\"login.php\">Login</a>";
?>

</body>
</html>