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

// Make sure it is the user owns the post
if (isset($_SESSION['email']) && isset($post['email']) && $_SESSION['email'] == $post['email']) {
  // Delete their picture

  // Check if the old image exists before trying to delete it
  if (file_exists($post['postImage'])) {
      // Delete the old image
      if (!unlink($post['postImage'])) {
          // Handle error if unlink fails
          echo "Error: Failed to delete old image.";
      }
  } else {
      // Handle case where the file doesn't exist
      echo "Warning: Old image file not found.";
  }

  // Delete the post in the array
  unset($posts[$postIndex]);

  file_put_contents('./data/posts.json', json_encode($posts, JSON_PRETTY_PRINT));

}

header('Location: profile.php');

