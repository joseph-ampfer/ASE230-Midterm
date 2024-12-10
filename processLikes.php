<?php
session_start();
require_once('db.php');
if (isset($_SESSION['ID'])) {
    $userId = $_SESSION['ID'];
} else {
    header("Location: index.php");
}

// handling the post request sent from the axios post to update the likes count
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data (JSON)
    $input = json_decode(file_get_contents('php://input'), true);

    // Access the data sent from the frontend
    $post_id = $input['post_id'];
    $increase_like = $input['increase_like'];
    // handle the likes count
    if ($increase_like) {
        $stmt = $db->prepare("INSERT INTO `post_likes` (`post_id`, `user_id`) VALUES (?, ?)"); /** @var PDOStatement $stmt */
        $stmt->execute([$post_id, $userId]);
    } else {
        $stmt = $db->prepare("DELETE FROM `post_likes` WHERE `post_likes`.`post_id` = ?"); /** @var PDOStatement $stmt */
        $stmt->execute([$post_id]);
    }

    echo json_encode([
        'status' => 'success',
        'message' => "Post id to update = $post_id"
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>