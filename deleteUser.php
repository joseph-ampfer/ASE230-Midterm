<?php
session_start();
if (!$_SESSION['isAdmin']) {
    header("Location: index.php");
    exit;
}

require_once('./db.php');
$userToDelete = $_POST['id'] ?? null;
if ($userToDelete) {
    $stmt = $db->prepare('DELETE FROM users WHERE id = :id');
    $success = $stmt->execute(['id' => $userToDelete]);

    if ($success) {
        // Header back to the admins page with a success message
        header('Location: admin.php?message=User deleted successfully');
        exit;
    } else {
        // Header back with an error message
        header('Location: admin.php?error=Failed to delete user');
        exit;
    }
} else {
    // Header back with an error message
    header('Location: admin.php?error=Invalid user ID');
    exit;
}
?>