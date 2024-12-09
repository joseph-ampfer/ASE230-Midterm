<?php

/**
 * Save a comment to the database.
 *
 * @param PDO $pdo The PDO database connection object.
 * @param int $postIndex The ID of the post the comment belongs to.
 * @param array $data An associative array containing the comment data (username, comment).
 */
function newsaveComment($pdo, $postID, $data)
{
  // Prepare the SQL query to insert the comment
  $stmt = $pdo->prepare(
    "INSERT INTO comments (post_id, user_id, comment) VALUES (:post_id, :user_id, :comment)"
  );

  /** @var PDOStatement $stmt */
  // Execute the query
  $stmt->execute([
    'post_id' => $postID,
    'user_id' => $_SESSION['ID'],
    'comment' => $data['comment']
  ]);

}


/**
 * Save a comment to the database.
 *
 * @param PDO $pdo The PDO database connection object.
 * @param int $user_id The ID of the post the comment belongs to.
 */
function getUserInfo($pdo, $user_id)
{
  $stmt = $pdo->prepare(
    "SELECT firstname, lastname, picture, short_bio, major, social_link FROM users WHERE id=?"
  ); /** @var PDOStatement $stmt */

  $stmt->execute([$user_id]);
  return $stmt->fetch();
}


function editPost($filePath, $data, $index)
{
  $existingData = [];
  if (file_exists($filePath)) {
    $existingData = json_decode(file_get_contents($filePath), true);
  }

  $existingData[$index] = $data; // Edit data at the posts index
  file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
}


function formatDate($time)
{
  // Create a Datetime object from the ISO 8601 string
  $date = new DateTime($time);

  // Format it as "2 Feb 2019"
  return $date->format('j M Y');
}

function formatTime($time)
{
  // Create a Datetime object from the ISO 8601 string
  $date = new DateTime($time);

  // Format it as "2 Feb 2019"
  return $date->format('g: i A');
}


function checkIfLiked($db, $userID, $postId)
{
  $stmt = $db->prepare("SELECT COUNT(*) FROM post_likes WHERE user_id = ? AND post_id = ?");
  $stmt->execute([$userID, $postId]);
  $likeCount = $stmt->fetchColumn();
  // Return true if the user has liked the post, false otherwise
  return $likeCount > 0;
}
