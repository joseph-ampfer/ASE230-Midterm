<?php

require_once('scripts/scripts.php');
require_once('db.php');
require_once('Auth.php');

$isLoggedIn = Auth::isLoggedIn();
$showAdminPage = Auth::isAdmin();

if (!$isLoggedIn) {
	header("Location: login.php");
}

$error = "";

$userToEditID = $_SESSION['ID'];

if ($_SESSION['isAdmin'] && isset($_GET['id'])) {
	$userToEditID = $_GET['id'];
}

$userInfo = getUserInfo($db, $userToEditID);

// Initiate error
$error = "";

// To post, check if logged and data is there
if ($isLoggedIn && count($_POST) > 0) {
	if (isset($_POST['postTitle'][0])) {

		// Check if required fields have values in $_POST
		if (empty($_POST['postTitle'])) {
			$error = "Post title is required.";
		} elseif (empty($_POST['postCategories'])) {
			$error = "Post categories are required.";
		} elseif (empty($_POST['lookingFor'])) {
			$error = "Looking for field is required.";
		} elseif (empty($_POST['description'])) {
			$error = "Description is required.";
		}

		if (isset($_FILES['postImage']) && $_FILES['postImage']['error'] === UPLOAD_ERR_OK && $error === "") {

			// Allowed MIME types (covers most common image formats)
			$allowedMimeTypes = [
				'image/jpeg',
				'image/png',
				'image/gif',
				'image/webp',
				'image/bmp',
				'image/tiff',
				'image/svg+xml'
			];

		
			try {

				// Validate Mime type
				$detectedType = mime_content_type($_FILES['postImage']['tmp_name']);
				if (!in_array($detectedType, $allowedMimeTypes)) {
					throw new InvalidArgumentException("Must upload an image (jpeg, jpg, png, gif)");
				}

				// Create image file path
				$fextension = pathinfo($_FILES['postImage']['name'], PATHINFO_EXTENSION);
				$time = time();
				$imagePath = './assets/images/blog/' . $time . '.' . $fextension;

				// Begin the transaction
				$db->beginTransaction();

				// Insert post, get its id
				$stmt = $db->prepare("INSERT INTO posts (user_id, title, description, image) VALUES (?, ?, ?, ?) "); /** @var PDOStatement $stmt */
				$stmt->execute([$userToEditID, $_POST['postTitle'], $_POST['description'], $imagePath ]);
				$post_id = $db->lastInsertId();

				// Decode the categories and looking for
				$postCategories = json_decode($_POST['postCategories'], true);
				$lookingFor = json_decode($_POST['lookingFor'], true);

				// Insert the categories
				foreach($postCategories as $row) {
					$cmd = $db->prepare("INSERT INTO post_categories (post_id, category) VALUES (?, ?)"); /** @var PDOStatement $cmd */
					$cmd->execute([$post_id, $row['value']]);
				}

				// Insert the looking for
				foreach($lookingFor as $row) {
					$cmd = $db->prepare("INSERT INTO looking_for (post_id, role) VALUES (?, ?)"); /** @var PDOStatement $cmd */
					$cmd->execute([$post_id, $row['value']]);
				}

				// Only upload image to server if all else worked
				move_uploaded_file($_FILES['postImage']['tmp_name'], $imagePath);

				// Commit the transaction
				$db->commit();
				header("Location: profile.php");

			} catch(Exception $e) {
				if ($db->inTransaction()) {
						$db->rollBack();
				}

				// Handle the error (log it, display an error message, etc.)
				echo "Transaction failed: " . $e->getMessage();
				$error = $e->getMessage();
			}
		}
	}
}

// Get all of the users posts with SESSION ID
try {
	$q = "
		SELECT 
			u.firstname, 
			u.lastname, 
			u.picture,
			u.major,
			u.social_link,
			u.short_bio,
			u.id AS user_id,
			p.id AS post_id,
			p.title,
			p.image,
			p.created_at, 
			SUBSTRING(p.description, 1, 100) AS short_description,
			GROUP_CONCAT(DISTINCT category SEPARATOR ', ') AS categories, 
			GROUP_CONCAT(DISTINCT role SEPARATOR ', ') AS roles,
			COUNT(DISTINCT pl.user_id) AS like_count,
			COUNT(DISTINCT cm.id) AS comment_count
		FROM posts p
		LEFT JOIN post_categories pc ON p.id = pc.post_id
		LEFT JOIN looking_for lf ON p.id = lf.post_id
		LEFT JOIN users u ON p.user_id = u.id
		LEFT JOIN post_likes pl ON p.id = pl.post_id
		LEFT JOIN comments cm ON p.id = cm.post_id
		WHERE u.id = ? 
		GROUP BY p.id
		ORDER BY p.created_at DESC
	";
	$cmd = $db->prepare($q); /** @var PDOStatement $cmd */
	$cmd->execute([$userToEditID]);
	$posts = $cmd->fetchAll();


} catch(Exception $e) {
	if ($db->inTransaction()) {
			$db->rollBack();
	}

	// Handle the error (log it, display an error message, etc.)
	echo "Transaction failed: " . $e->getMessage();
	$error = $e->getMessage();
}


// To edit USER INFO
if ($isLoggedIn && count($_POST) > 0) {

	try {

		if (!empty($_FILES['postImage']['tmp_name']) && $_FILES['postImage']['error'] !== UPLOAD_ERR_OK) {
			throw new Exception("Error uploading file: " . $_FILES['postImage']['error']);
		}

		// If they are uploading a new image
		if (!empty($_FILES['postImage']['tmp_name'])) {
			// Allowed MIME types (covers most common image formats)
			$allowedMimeTypes = [
				'image/jpeg',
				'image/png',
				'image/gif',
				'image/webp',
				'image/bmp',
				'image/tiff',
				'image/svg+xml'
			];

			// Validate Mime type
			$detectedType = mime_content_type($_FILES['postImage']['tmp_name']);
			if (!in_array($detectedType, $allowedMimeTypes)) {
				throw new InvalidArgumentException("Must upload an image (jpeg, jpg, png, gif)");
			}


			// Create image file path
			$fextension = pathinfo($_FILES['postImage']['name'], PATHINFO_EXTENSION);
			$time = time();
			$imagePath = './assets/images/profile_pictures/' . $time . '.' . $fextension;
			// Only upload image to server if all else worked
			move_uploaded_file($_FILES['postImage']['tmp_name'], $imagePath);

		} else {
			$imagePath=$userInfo['picture'];
		}

		// Begin the transaction
		$db->beginTransaction();

		$majorArray = json_decode($_POST['major'], true);
		$major=$majorArray[0]['value'];

		// Update post, get its id
		$stmt = $db->prepare("UPDATE users SET major=?, social_link=?, picture=?, short_bio=? WHERE id=?"); /** @var PDOStatement $stmt */
		$stmt->execute([$major, $_POST['social_link'], $imagePath, $_POST['short_bio'], $userToEditID ]);


		// Commit the transaction
		$db->commit();
		header("Refresh:0");
		
	} catch(Exception $e) {
		if ($db->inTransaction()) {
				$db->rollBack();
		}

		// Handle the error (log it, display an error message, etc.)
		echo "Transaction failed: " . $e->getMessage();
		$error = $e->getMessage();
	}
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>U Collab</title>
	<script src="https://cdn.tailwindcss.com"></script>
	<link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">
	<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500%7CSpectral:400,400i,500,600,700"
		rel="stylesheet">
	<!-- <link rel="stylesheet" href="assets/css/bootstrap.min.css"> -->

	<!-- Include Bootstrap 5 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/plugins/animate/animate.min.css">
	<link rel="stylesheet" href="assets/plugins/owl-carousel/owl.carousel.min.css">
	<link rel="stylesheet" href="assets/plugins/magnific-popup/magnific-popup.css">
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/responsive.css">
	<link rel="stylesheet" href="assets/css/custom.css">



	<!-- FAVICONS FOR DIFFERENT DEVICES -->
	<link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="assets/images/favicon/site.webmanifest">
	<link rel="mask-icon" href="assets/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
</head>

<body>
	<!-- <div class="preloader">
		<div class="preload-img">
			<div class="spinnerBounce">
				<div class="double-bounce1"></div>
				<div class="double-bounce2"></div>
			</div>
		</div>
	</div> -->
	<div class="nav-search-box">
		<form>
			<div class="input-group"> <input type="text" class="form-control" placeholder="eg. feel the love and …">
				<span class="b-line"></span> <span class="b-line-under"></span>
				<div class="input-group-append"> <button type="button" class="btn">
						<img src="assets/images/search-icon.svg" alt="" class="img-fluid svg" />
					</button>
				</div>
			</div>
		</form>
	</div>
	<header class="header">
		<div class="header-fixed" style="background-color:#fcfcfc">
			<div class="container-fluid pl-120 pr-120 position-relative">
				<div class="row d-flex align-items-center">
					<div class="col-lg-3 col-md-4 col-6">
						<div class="logo"> <a href="#"><img src="assets/images/logo.png" alt="" class="img-fluid"
									style="height: 100px;"></a>
						</div>
					</div>
					<div class="col-lg-9 col-md-8 col-6 d-flex align-items-center justify-content-end position-static">
						<div class="nav-menu-cover">
							<ul class="nav nav-menu align-items-center">
								<li><a href="index.php">Home</a></li>
								<li><a href="about.php">About</a></li>
								<li><a href="contact.php">Contact</a></li>
								<?php if($showAdminPage) echo '<li><a href="admin.php">Admin Page</a></li>'?>
								<li><a href="contact.php"></a></li>
								<?php
								echo $isLoggedIn ?
									'<li class="dropdown">
										<!-- User image as the dropdown trigger with inline styles -->
										<img src="'.$userInfo['picture'].'"
												style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; cursor: pointer;"
												class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown"
												aria-expanded="false" alt="User Avatar">
								
										<!-- Dropdown menu -->
										<ul class="dropdown-menu" aria-labelledby="userDropdown">
												<li><a class="dropdown-item" href="profile.php">Profile</a></li>
												<li><a class="dropdown-item" href="#">Settings</a></li>
												<li><a class="dropdown-item" href="#">Help</a></li>
												<li><a class="dropdown-item" href="#"></a></li>
												<li><hr class="dropdown-divider"></li>
													<li>
															<form method="POST" action="logout.php">
																	<button type="submit" class="dropdown-item">Sign out</button>
															</form>
													</li>
											</ul>
									</li>' :
									'<li><a href="login.php">Log in</a></li>';
								?>
							</ul>
						</div>
						<div class="mobile-menu-cover">
							<ul class="nav mobile-nav-menu">
								<li class="search-toggle-open">
									<img src="assets/images/search-icon.svg" alt="" class="img-fluid svg">
								</li>
								<li class="search-toggle-close hide">
									<img src="assets/images/close.svg" alt="" class="img-fluid">
								</li>
								<li class="nav-menu-toggle">
									<img src="assets/images/menu-toggler.svg" alt="" class="img-fluid svg">
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>


	<!-- Main content -->
	<main class="container pt-15 pb-90">

		<!-- User's Profile Card -->
		<div class="d-flex justify-content-center align-items-center" style="height: 50vh; background-color: #f8f9fa;">
			<div class="card text-center" style="width: 50rem; border: none;">
				<!-- Profile Image -->
				<div class="card-img-top">
					<img src="<?= $userInfo['picture'] ?>" alt="Profile" class="rounded-circle"
						style="width: 120px; height: 120px; margin: auto; display: block; object-fit: cover;">
				</div>


				<div class="card-body">
					<!-- Name -->
					<h5 class="card-title mb-1"><?= $userInfo['firstname'].' '.$userInfo['lastname'] ?></h5>

					<!-- Major -->
					<p class="text-muted" style="font-size: 14px; margin: 0;"><?= $userInfo['major'] ?></p>
					<!-- Social Medial Link -->
					<a href="<?= $userInfo['social_link'] ?>" target="_blank"
						style="margin-right: 10px; text-decoration: none; color: #1DA1F2;">
						<?= $userInfo['social_link'] ?>
					</a>
					<!-- Short Bio -->
					<p class="card-text mt-3" style="font-size: 15px; color: #6c757d;">
						<?= $userInfo['short_bio'] ?>
					</p>
					<!-- Edit/Update Button -->
					<div class="text-end" style="margin: 10px auto;">
						<button type="button" class="btn" title="Update profile" onClick="showUpdateForm()">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
								class="bi bi-pencil-fill" viewBox="0 0 16 16">
								<path
									d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
							</svg>
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Form to update user details -->
		<div id="formContainer" style="display: none; 
			background-color: #f1f3f5; 
			padding: 20px; 
			border-radius: 10px; 
			margin-top: 20px; 
			overflow: hidden; 
			height: 0; 
			transition: height 0.5s ease;">
			<form id="userDetailsForm" class="comment-form" method="POST" enctype="multipart/form-data">
				<div class="row">
					<!-- Update Profile Picture Field -->
					<div class="col-md-12 mb-3 d-flex align-items-center">
						<label for="postImage" class="me-2"><strong>Profile Picture</strong></label>
						<input  type="file" class="form-control me-2" name="postImage" accept="image/*">

					</div>

					<!-- Major -->
					<div class="col-md-12 mb-3 d-flex align-items-center">
						<label for="major" class="me-2"><strong>Major</strong></label>
						<input name="major" class="form-control me-2 w-50" placeholder="Choose your major"
							value="<?= $userInfo['major'] ?>" data-blacklist="badwords, asdf">

					</div>

					<!-- Social Media Link -->
					<div class="col-md-12 mb-3 d-flex align-items-center">
						<label for="social_link" class="me-2"><strong>Social Media Link</strong></label>
						<input name="social_link" class="form-control me-2 w-50"
							placeholder="Add one of your social media links" value="<?= $userInfo['social_link'] ?>" data-blacklist="badwords, asdf">

					</div>

					<!-- Short Bio of User -->
					<div class="col-md-12 mb-3 d-flex align-items-center">
						<label for="short_bio" class="me-2"><strong>Your short bio</strong></label>
						<textarea class="form-control me-2" name="short_bio"
							placeholder="Describe about you...your hobbies...fun fact..."><?= $userInfo['short_bio'] ?></textarea>

					</div>
				</div>
				<div class="text-end" style="margin: 10px auto;">
					<button type="submit" class="btn btn-primary" onClick="showUpdateForm()">Update</button>
				</div>
			</form>
		</div>


		<!-- Post Modal Trigger -->
		<?php if ($isLoggedIn) { ?>
			<div class="container  mt-5 mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal"
				style="cursor: pointer; ">
				<div class="d-flex  justify-content-between">
					<div></div>
					<div
						class="d-flex justify-content-between rounded-pill h-25 w-25 align-items-center p-3 shadow-lg rounded cursor-pointer bg-light hover:bg-gray-200">
						<img src="assets/images/blog/author.png" alt="User Avatar"
							style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; cursor: pointer;" />
						<div class="ml-3 text-secondary">
							Post a Project
						</div>
						<span>
							<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor"
								class="bi bi-send" viewBox="0 0 16 16">
								<path
									d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
							</svg>
						</span>
					</div>
				</div>
			</div>
		<?php }
		; ?>
  <?php if (strlen($error) > 0) { ?>
    <div class="error-message"><?= $error ?></div>
  <?php } ?>
		<div class="row">

			<!-- v2 -->
			<?php 	/** @var array $posts */ 
			foreach ($posts as $key => $post) {
				//if (isset($_SESSION['email']) && isset($post['email']) && $_SESSION['email'] == $post['email']) { ?>

					<div class="col-md-6">

						<div class="z-100 bg-slate-50 flex justify-between ">
							<a class="bg-red-300 text-white px-5 py-2 hover:bg-red-500"
								href="deletePost.php?id=<?= $post['post_id'] ?>">Delete</a>
							<a class="bg-gray-950 text-white px-5 py-2 hover:bg-gray-950/70"
								href="editPost.php?id=<?= $post['post_id'] ?>">Edit</a>
						</div>

						<div class="post-default post-has-bg-img">
							<div class="post-thumb">
								<a href="details-full-width.php">
									<div data-bg-img=<?= $post['image'] ?>></div>
								</a>
							</div>
							<div class="post-data">
								<div class="cats">
									<?php 
									$categories = explode(", ", $post['categories']);
									foreach ($categories as $category) { ?>
										<a href="category-result.html"><?= $category ?></a>
									<?php } ?>
								</div>
								<div class="title mb-1">
									<h2><a href="details-full-width.php?id=<?= $post['post_id'] ?>"><?= $post['title'] ?></a></h2>
								</div>
								<p class="shortDescription mb-5 px-10">
									<?= $post['short_description']. '...'  ?>
								</p>
								<!-- Shortened project description -->
								<div>
									<p>Looking for:</p>
									<div class="flex space-x-2 items-center justify-center">
										<?php 
										$roles = explode(", ", $post['roles']);
										foreach ($roles as $role) { ?>
											<span class="bg-white/10 p-2 text-white"><?= $role ?></span>
										<?php } ?>
									</div>
								</div>
								<ul class="nav meta align-items-center absolute bottom-0 left-0 ml-5">
									<li class="meta-author flex items-center justify-center space-x-2">
										<img src="<?= !empty($post['picture']) ? $post['picture'] : 'default-avatar.png' ?>"
											alt="" class="img-fluid">
										<a class="text-white/80" href="#"><?= $post['firstname'].' '.$post['lastname'] ?></a>
									</li>
									<li class="meta-date"><a class="text-white/80"
											href="#"><?= formatDate($post['created_at']) ?></a></li>
									<li class="meta-comments"><a class="text-white/80" href="#"><i
												class="fa fa-comment text-white/80"></i> <?= $post['comment_count'] ?></a>
									</li>
									<li class="meta-likes"><a class="text-white/80" href="#"><i
												class="fa fa-heart text-white/80"></i> <?= $post['like_count'] ?? 0 ?></a></li>
									<!-- Optional likes feature -->
								</ul>
								<!-- <div class="join-project">
								<a href="contact-owner.php?id=" class="btn btn-primary">Join Project</a>
							</div> -->
							</div>
						</div>
					</div>
				<?php }
			  ?>



		</div>
		<!-- Pagination below posts -->
		<div class="post-pagination d-flex justify-content-center">
			<span class="current">1</span>
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#"><i class="fa fa-angle-right"></i></a>
		</div>
	</main>

	<!-- Subscribe to our newsletter -->
	<section class="newsletter-cover">
		<div class="nl-bg-ol"></div>
		<div class="container">
			<div class="newsletter pt-80 pb-80">
				<div class="section-title text-center">
					<h2>Subscribe Our Newsletter</h2>
				</div>
				<div class="row">
					<div class="col-lg-8 offset-lg-2">
						<form
							action="https://themelooks.us13.list-manage.com/subscribe/post?u=79f0b132ec25ee223bb41835f&amp;id=f4e0e93d1d"
							method="post" novalidate>
							<div class="input-group"> <input type="text" class="form-control"
									placeholder="Enter Your Email">
								<div class="input-group-append"> <button class="btn btn-default">Submit</button> </div>
							</div>
							<p class="checkbox-cover d-flex justify-content-center"> <label> I've read and accept the <a
										href="#"> Privacy Policy </a> <input type="checkbox"> <span
										class="checkmark"></span> </label> </p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<footer class="footer-container d-flex align-items-center">
		<div class="container">
			<div class="row align-items-center footer">
				<div class="col-md-4 text-center text-md-left order-md-1 order-2">
					<div class="footer-social"> <a href="#"><i class="fa fa-facebook"></i></a> <a href="#"><i
								class="fa fa-twitter"></i></a> <a href="#"><i class="fa fa-linkedin"></i></a> <a
							href="#"><i class="fa fa-google"></i></a> <a href="#"><i class="fa fa-pinterest"></i></a>
					</div>
				</div>
				<div class="col-md-4 d-flex justify-content-center order-md-2 order-1"> <a href="index.php"><img
							src="assets/images/logo.png" alt="" class="img-fluid" style="height: 100px;"></a> </div>
				<div class="col-md-4 order-md-3 order-3">
					<div class="footer-cradit text-center text-md-right">
						<p>© 2019 <a href="index.html">Themelooks.</a></p>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<div class="back-to-top d-flex align-items-center justify-content-center"> <span><i
				class="fa fa-long-arrow-up"></i></span> </div>

	<!-- ============= POST PROJECT MODAL ================= -->
	<?php require_once 'components/postProjectModal.php' ?>


	<script src="assets/js/jquery-1.12.1.min.js"></script>
	<!-- <script src="assets/js/bootstrap.bundle.min.js"></script> -->

	<!-- Include Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Tagify -->
	<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
	<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.polyfills.min.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />

	<script src="assets/plugins/owl-carousel/owl.carousel.min.js"></script>
	<script src="assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
	<script src="assets/js/scripts.js"></script>
	<script src="assets/js/custom.js"></script>
	<script>


var majorInputElm = document.querySelector('input[name=major]');
		// Define the list of project categories
		var whitelist = [
			"Applied Software Engineering",
			"Computer Science",
			"Business Administration",
			"Mechanical Engineering",
			"Biology",
			"Psychology",
			"Electrical Engineering",
			"Nursing",
			"Economics",
			"Civil Engineering",
			"Chemistry",
			"Marketing",
			"Political Science",
			"Environmental Science",
			"Sociology",
			"Architecture",
			"Finance",
			"Mathematics",
			"Education",
			"Communications",
			"Physics",
			"Graphic Design",
			"Criminal Justice",
			"Aerospace Engineering",
			"Anthropology",
			"Public Health",
			"International Relations",
			"Data Science",
			"Biomedical Engineering",
			"Music",
			"Information Technology"
		];
				// Initialize Tagify on the input element
		var majorTagify = new Tagify(majorInputElm, {
			whitelist: whitelist, // Use the predefined whitelist array
			enforceWhitelist: true, // Only allow items from the whitelist
			maxTags: 1, // Limit the number of tags
			dropdown: {
				maxItems: 20, // Maximum items to show in the dropdown
				classname: "suggestions", // Custom class name for styling
				enabled: 0, // Show suggestions on focus
				closeOnSelect: false // Keep the dropdown open after selecting a tag
			},
			//originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
		});


		var categoriesInputElm = document.querySelector('input[name=postCategories]');

		// Define the list of project categories
		var whitelist = [
			"Web Development",
			"Mobile Apps",
			"Game Development",
			"Data Science",
			"Machine Learning",
			"AI Projects",
			"Software Development",
			"Robotics",
			"IoT Projects",
			"Embedded Systems",
			"Mechanical Engineering",
			"Electrical Engineering",
			"Civil Engineering",
			"Biomedical Engineering",
			"Graphic Design",
			"UI/UX Design",
			"Animation",
			"Video Production",
			"Writing",
			"Literature",
			"Music Composition",
			"Theater",
			"Business",
			"Marketing",
			"Finance",
			"Entrepreneurship",
			"Educational Projects",
			"Environmental Projects",
			"Health & Medicine",
			"Social Impact",
			"Psychology",
			"History",
			"Cryptocurrency",
			"Blockchain",
			"Virtual Reality",
			"Augmented Reality",
			"Fashion Design",
			"Sports",
			"Physical Fitness"
		];

		// Initialize Tagify on the input element
		var tagify = new Tagify(categoriesInputElm, {
			whitelist: whitelist, // Use the predefined whitelist array
			enforceWhitelist: false, // Only allow items from the whitelist
			maxTags: 10, // Limit the number of tags
			dropdown: {
				maxItems: 20, // Maximum items to show in the dropdown
				classname: "suggestions", // Custom class name for styling
				enabled: 0, // Show suggestions on focus
				closeOnSelect: false // Keep the dropdown open after selecting a tag
			},
			//originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
		});

		var collaboratorInputElm = document.querySelector('input[name=lookingFor]');

		var lookingForWhitelist = [
			"Software Developer",
			"Front-End Developer",
			"Back-End Developer",
			"Full Stack Developer",
			"Mobile App Developer",
			"Game Developer",
			"Data Scientist",
			"Machine Learning Engineer",
			"AI Specialist",
			"Web Designer",
			"Graphic Designer",
			"UI/UX Designer",
			"Animator",
			"Video Editor",
			"Content Writer",
			"Technical Writer",
			"Copywriter",
			"Project Manager",
			"Business Analyst",
			"Marketing Specialist",
			"Social Media Manager",
			"SEO Specialist",
			"Product Manager",
			"Entrepreneur",
			"Startup Mentor",
			"Finance Specialist",
			"Marketing Strategist",
			"Photographer",
			"Music Composer",
			"Sound Engineer",
			"Hardware Engineer",
			"Robotics Specialist",
			"Mechanical Engineer",
			"Electrical Engineer",
			"Biomedical Engineer",
			"Civil Engineer",
			"Environmental Scientist",
			"Researcher",
			"Physicist",
			"Chemist",
			"Biologist",
			"Community Organizer",
			"Public Health Specialist",
			"Educator",
			"Teacher",
			"Student Mentor",
			"Legal Advisor",
			"Fashion Designer",
			"Artist",
			"3D Modeler",
			"DevOps Engineer",
			"Cybersecurity Specialist",
			"Blockchain Developer",
			"Cryptocurrency Expert",
			"Virtual Reality Specialist",
			"Augmented Reality Specialist",
			"Game Designer",
			"Scriptwriter",
			"Voice Actor",
			"Performance Artist",
			"Psychologist",
			"Sociologist",
			"Philosopher",
			"Historian",
			"Language Specialist",
			"Translator",
			"Linguist",
			"Athlete",
			"Coach",
			"Community Volunteer",
			"Other"
		];


		// Initialize Tagify on the collaborator input element
		var collaboratorTagify = new Tagify(collaboratorInputElm, {
			whitelist: lookingForWhitelist, // Use the predefined list of collaborator roles
			enforceWhitelist: false, // Allow additional roles if needed
			maxTags: 10, // Limit to 10 tags for collaboration roles
			dropdown: {
				maxItems: 20, // Maximum items to show in the dropdown
				classname: "suggestions", // Custom class name for styling
				enabled: 0, // Show suggestions on focus
				closeOnSelect: false // Keep dropdown open after selecting a tag
			},
			//originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
		});


		//show the edit or update form only when the edit button is clicked in the profile card
		function showUpdateForm() {
			const form = document.getElementById('formContainer');
			if (form.style.height === '0px' || form.style.height === '') {
				form.style.display = 'block';
				const fullHeight = form.scrollHeight + 'px';
				form.style.height = fullHeight;
				setTimeout(() => {
					form.style.height = 'auto';
				}, 500);
			} else {
				form.style.height = form.scrollHeight + 'px';
				setTimeout(() => {
					form.style.height = '0';
				}, 10);
				setTimeout(() => {
					form.style.display = 'none';
				}, 500);
			}
		}
	</script>

</body>

</html>