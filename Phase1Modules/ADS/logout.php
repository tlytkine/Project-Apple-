<?php
// Simple login script that destroys an session variables,
// including username/password
session_start();
if (session_destroy()) {
	header("Location: login.php");
}
?>