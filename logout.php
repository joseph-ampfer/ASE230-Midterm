<?php
// Start session
session_start();

// Destroy the session
session_destroy();

// Optionally, unset session variables
unset($_SESSION['user']); // Example, depends on how you've stored the session

// Set logged in status to false
$isLoggedIn = false;

// Redirect to homepage or login page
header('Location: login.php');
