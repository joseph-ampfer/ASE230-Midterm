<?php
if (!$isLoggedIn || count($_POST) === 0) {
    return; // Exit if not logged in or no post data submitted.
}

if (!isset($_POST['postTitle'][0]) || $post['email'] !== $_SESSION['email']) {
    return; // Exit if post title is missing or user isn't the author.
}

$data = $_POST;
$error = "";

// Handle image upload if a new image is provided.
if (isset($_FILES['postImage']) && $_FILES['postImage']['error'] === UPLOAD_ERR_OK) {
    handleImageUpload($post, $data, $error);
} else {
    $data['postImage'] = $post['postImage']; // Retain the old image.
}

// If no error, finalize and save the post.
if ($error === "") {
    finalizePostData($post, $data);
    editPost('data/posts.json', $data, $postIndex);
    header("Location: profile.php");
}

// Function to handle image upload and replacement.
function handleImageUpload($post, &$data, &$error) {
    // Delete the old image if it exists.
    if (file_exists($post['postImage']) && !unlink($post['postImage'])) {
        echo "Error: Failed to delete old image.";
    }

    $allowedMimeTypes = [
        'image/jpeg', 'image/png', 'image/gif',
        'image/webp', 'image/bmp', 'image/tiff', 'image/svg+xml'
    ];

    $detectedType = mime_content_type($_FILES['postImage']['tmp_name']);

    if (!in_array($detectedType, $allowedMimeTypes)) {
        $error = "Must upload an image (jpeg, jpg, png, gif, webp, bmp, svg)";
        return;
    }

    $extension = pathinfo($_FILES['postImage']['name'], PATHINFO_EXTENSION);
    $newImagePath = './assets/images/blog/' . uniqid() . '.' . $extension;

    if (move_uploaded_file($_FILES['postImage']['tmp_name'], $newImagePath)) {
        $data['postImage'] = $newImagePath;
    } else {
        $error = "Error: Failed to upload new image.";
    }
}

// Function to prepare post data for saving.
function finalizePostData($post, &$data) {
    $data['postTime'] = date("Y-m-d H:i:s");
    $data['likes'] = $post['likes'];
    $data['comments'] = $post['comments'];
    $data['authorName'] = $post['authorName'];
    $data['email'] = $post['email'];

    $postCategories = json_decode($_POST['postCategories'], true);
    $lookingFor = json_decode($_POST['lookingFor'], true);

    $data['postCategories'] = array_column($postCategories, 'value');
    $data['lookingFor'] = array_column($lookingFor, 'value');
}
