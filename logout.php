<?php
// Start session
session_start();

// Destroy the session
session_destroy();

// Redirect to homepage or login page
header('Location: login.php');
