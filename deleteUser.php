<?php
session_start();
if (!$_SESSION['isAdmin']) {
    header("Location: index.php");
    exit;
}

require_once('./db.php');
$userToDelete = $_POST['id'] ?? null;
if ($userToDelete) {

    $stmt = $db->prepare('DELETE FROM comments WHERE user_id = :id');
    /** @var PDOStatement $stmt */
    $stmt->execute(['id' => $userToDelete]);

    $stmt = $db->prepare('DELETE FROM post_likes WHERE user_id = :id');
    /** @var PDOStatement $stmt */
    $stmt->execute(['id' => $userToDelete]);

    $stmt = $db->prepare('DELETE FROM looking_for WHERE post_id IN (SELECT id FROM posts WHERE user_id = :id)');
    /** @var PDOStatement $stmt */
    $stmt->execute(['id' => $userToDelete]);

    $stmt = $db->prepare('DELETE FROM post_categories WHERE post_id IN (SELECT id FROM posts WHERE user_id = :id)');
    /** @var PDOStatement $stmt */
    $stmt->execute(['id' => $userToDelete]);

    $stmt = $db->prepare('DELETE FROM posts WHERE user_id = :id');
    /** @var PDOStatement $stmt */
    $stmt->execute(['id' => $userToDelete]);

    $stmt = $db->prepare('DELETE FROM users WHERE id = :id'); /** @var PDOStatement $stmt */
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