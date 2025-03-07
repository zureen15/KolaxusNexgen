<?php
session_start(); // Start the session

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Regenerate session ID to prevent session fixation
session_start();
session_regenerate_id(true);

// Redirect to login page
header("Location: login_form.php");
exit();
?>