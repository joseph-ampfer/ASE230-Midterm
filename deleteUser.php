<?php
session_start();
if (!$_SESSION['isAdmin']) {
    header("Location: index.php");
    exit;
}

require_once('./db.php');

// Check if the form was submitted with a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        // Use prepared statements to prevent SQL injection
        $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $success = $stmt->execute();

        if ($success) {
            // Redirect back to the users page with a success message
            header('Location: admin.php?message=User deleted successfully');
            exit;
        } else {
            // Redirect back with an error message
            header('Location: admin.php?error=Failed to delete user');
            exit;
        }
    } else {
        // Redirect back with an error message
        header('Location: admin.php?error=Invalid user ID');
        exit;
    }
} else {
    // If the request method isn't POST, redirect to the users page
    header('Location: admin.php');
    exit;
}
?>
