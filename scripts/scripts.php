<?php

function readJsonData($filepath)
{
  if (file_exists($filepath)) {
    $jsonData = file_get_contents($filepath);
    return json_decode($jsonData, true);
  }
  return [];
}
// Example usage:
//$data = readJsonData('data/uv_readings.json');


function saveToJson($filePath, $data)
{
  $existingData = [];
  if (file_exists($filePath)) {
    $existingData = json_decode(file_get_contents($filePath), true);
  }

  $existingData[] = $data; // Append new data to existing array
  file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
}
//======= Example usage =============================
// $newReading = [
//   "id" => 3,
//   "uv_index" => 7,
//   "timestamp" => date("Y-m-d H:i:s"),
//   "location" => "New York",
//   "intensity" => "Very High"
// ];

// saveToJson('data/uv_readings.json', $newReading);
// ==================================================


function saveComment($filePath, $postIndex, $data)
{
  $existingData = [];
  if (file_exists($filePath)) {
    $existingData = json_decode(file_get_contents($filePath), true);
  }
  // Add current timestamp
  $dateTime = date("Y-m-d H:i:s");

  $data["time"] = $dateTime;

  $existingData[$postIndex]['comments'][] = $data; // Append new data to existing array
  file_put_contents($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
}

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
function getUserInfo($pdo, $user_id) {
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

function getUserName($email)
{
  $file = fopen('data/users.csv.php', 'r'); // Open the file in read mode

  // Check through each line
  while (($line = fgets($file)) !== false) {
    $data = explode(';', trim($line)); // Split the line by semicolons and remove any extra spaces

    // Check if the email in the line matches the provided email
    if ($data[0] === $email) {
      fclose($file);
      return $data[2] . ' ' . $data[3];
    }
  }

  fclose($file);
  return null;
}
