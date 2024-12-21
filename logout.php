<?php
session_start();
session_destroy(); // Destroy all sessions
setcookie('user_id', '', time() - 3600, "/"); // Clear any cookies
setcookie('user_name', '', time() - 3600, "/");
header("Location: login.php"); // Redirect to login page
exit();
