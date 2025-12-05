<?php
session_start();

// Destroy the session and clear all session variables
session_unset();
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit();
?>
