<?php
session_start();
require_once('scripts/scripts.php');

$isLoggedIn = false;
if (isset($_SESSION['email'])) {
    $isLoggedIn = true;
} else {
  header("Location: login.php");
}

// Index for the post 
$postIndex = $_GET['id'];

$posts = readJsonData('./data/posts.json');
$post = $posts[$postIndex];

require_once('db.php');
try {
  // Begin transaction
  $db->beginTransaction();

  // Confirm they own the post
  $stmt = $db->prepare("SELECT user_id FROM posts WHERE id = ?"); /** @var PDOStatement $stmt */
  $stmt->execute([$postIndex]);
  $owner_id = $stmt->fetchColumn();

  if ($owner_id != $_SESSION['ID']) {
    throw new Exception("You do not own this post");
  }

  // Get image path, so we can delete it
  $stmt = $db->prepare("SELECT image FROM posts WHERE id = ?");   /** @var PDOStatement $stmt */
  $stmt->execute([$postIndex]);
  $imagePath = $stmt->fetchColumn();

  // Debug the image path
  if (!file_exists($imagePath)) {
      throw new Exception("Warning: File not found at path: " . $imagePath);
  }

  // Check if the file is writable
  if (!is_writable($imagePath)) {
      throw new Exception("Error: File is not writable: " . $imagePath);
  }

  // Attempt to delete the file
  if (!unlink($imagePath)) {
      throw new Exception("Error: Failed to delete the file: " . $imagePath);
  }

  $cmd = $db->prepare("DELETE FROM post_likes WHERE post_id = ? "); /** @var PDOStatement $cmd */
  $cmd->execute([$postIndex]);

  $cmd = $db->prepare("DELETE FROM comments WHERE post_id = ? "); /** @var PDOStatement $cmd */
  $cmd->execute([$postIndex]);

  $cmd = $db->prepare("DELETE FROM looking_for WHERE post_id = ? "); /** @var PDOStatement $cmd */
  $cmd->execute([$postIndex]);

  $cmd = $db->prepare("DELETE FROM post_categories WHERE post_id = ? "); /** @var PDOStatement $cmd */
  $cmd->execute([$postIndex]);

  $cmd = $db->prepare("DELETE FROM posts WHERE id = ? "); /** @var PDOStatement $cmd */
  $cmd->execute([$postIndex]);

  $db->commit();

  header('Location: profile.php');
} catch(Exception $e) {
  if ($db->inTransaction()) {
    $db->rollBack();
  }
  echo $e;
}


