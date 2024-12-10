<?php

require_once('scripts/scripts.php');
require_once('Auth.php');

$isLoggedIn = Auth::isLoggedIn();
$showAdminPage = Auth::isAdmin();

if (!$isLoggedIn) {
  header("Location: login.php");
}
require_once('db.php');

if ($isLoggedIn) {
	$userInfo = getUserInfo($db, $_SESSION['ID']);
}


// if user doesnt own the post, 
// or if they are not admin, return

// Id for the post page
$postIndex = $_GET['id'];
$error="";
// Get the post

try {
	$q = "
		SELECT 
			u.firstname, 
			u.lastname, 
			u.picture,
			u.id AS user_id,
			p.id AS post_id,
			p.title,
			p.image,
			p.created_at, 
			p.description,
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
		WHERE p.id = ?
		LIMIT 1
	";
	$cmd = $db->prepare($q); /** @var PDOStatement $cmd */
	$cmd->execute([$postIndex]);
	$post = $cmd->fetch();

	if ($post['user_id'] != $_SESSION['ID'] && !$_SESSION['isAdmin']) {
		throw new Exception("You do not own this post");
		header("Location: profile.php");
	}

} catch(Exception $e) {
	if ($db->inTransaction()) {
			$db->rollBack();
	}
	// Handle the error (log it, display an error message, etc.)
	echo "Transaction failed: " . $e->getMessage();
	$error = $e->getMessage();
}


// Initiate error
$error = "";
require_once('db.php');
// To edit a post, check if loggedin and data there
if ($isLoggedIn && count($_POST) > 0) {

	if ($post['user_id'] != $_SESSION['ID'] && !$_SESSION['isAdmin']) {
		throw new Exception("You do not own this post");
		header("Location: profile.php");
	}

	try {
		// Check if required fields have values in $_POST
		if (empty($_POST['postTitle'])) {
			throw new Exception("Post title is required.");
		} elseif (empty($_POST['postCategories'])) {
			throw new Exception("Post categories are required.");
		} elseif (empty($_POST['lookingFor'])) {
			throw new Exception("Looking for field is required.");
		} elseif (empty($_POST['description'])) {
			throw new Exception("Description is required.");
		}

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

			$oldImage = $post['image'];
			// Try to delete old image
			// Debug the image path
			if (!file_exists($oldImage)) {
					throw new Exception("Warning: File not found at path: " . $oldImage);
			}

			// Check if the file is writable
			if (!is_writable($oldImage)) {
					throw new Exception("Error: File is not writable: " . $oldImage);
			}

			// Attempt to delete the file
			if (!unlink($oldImage)) {
					throw new Exception("Error: Failed to delete the file: " . $oldImage);
			}

			// Create image file path
			$fextension = pathinfo($_FILES['postImage']['name'], PATHINFO_EXTENSION);
			$time = time();
			$imagePath = './assets/images/blog/' . $time . $_FILES['postImage']['name']. '.' . $fextension;
			// Only upload image to server if all else worked
			move_uploaded_file($_FILES['postImage']['tmp_name'], $imagePath);

		} else {
			$imagePath=$post['image'];
		}

		// Begin the transaction
		$db->beginTransaction();

		// Update post, get its id
		$stmt = $db->prepare("UPDATE posts SET title=?, description=?, image=? WHERE id=?"); /** @var PDOStatement $stmt */
		$stmt->execute([$_POST['postTitle'], $_POST['description'], $imagePath, $post['post_id'] ]);

		// Decode the categories and looking for
		$postCategories = json_decode($_POST['postCategories'], true);
		$lookingFor = json_decode($_POST['lookingFor'], true);

		$cmd = $db->prepare("DELETE FROM looking_for WHERE post_id = ? "); /** @var PDOStatement $cmd */
		$cmd->execute([$postIndex]);

		$cmd = $db->prepare("DELETE FROM post_categories WHERE post_id = ? "); /** @var PDOStatement $cmd */
		$cmd->execute([$postIndex]);

		// Insert the categories
		foreach($postCategories as $row) {
			$cmd = $db->prepare("INSERT INTO post_categories (post_id, category) VALUES (?, ?)"); /** @var PDOStatement $cmd */
			$cmd->execute([$post['post_id'], $row['value']]);
		}

		// Insert the looking for
		foreach($lookingFor as $row) {
			$cmd = $db->prepare("INSERT INTO looking_for (post_id, role) VALUES (?, ?)"); /** @var PDOStatement $cmd */
			$cmd->execute([$post['post_id'], $row['value']]);
		}

		// Commit the transaction
		$db->commit();
		header("Location: details-full-width.php?id=".$post['post_id']);
		
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
  <title>BizBlog</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">
  <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500%7CSpectral:400,400i,500,600,700" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">

  <!-- <script src="https://cdn.tailwindcss.com"></script> -->

  <!-- Include Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/font-awesome.min.css">
  <link rel="stylesheet" href="assets/plugins/animate/animate.min.css">
  <link rel="stylesheet" href="assets/plugins/owl-carousel/owl.carousel.min.css">
  <link rel="stylesheet" href="assets/plugins/magnific-popup/magnific-popup.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/responsive.css">
  <link rel="stylesheet" href="assets/css/custom.css">

  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="assets/images/favicon/site.webmanifest">
  <link rel="mask-icon" href="assets/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
</head>

<body>
  <div class="preloader">
    <div class="preload-img">
      <div class="spinnerBounce">
        <div class="double-bounce1"></div>
        <div class="double-bounce2"></div>
      </div>
    </div>
  </div>
  <div class="nav-search-box">
    <form>
      <div class="input-group"> <input type="text" class="form-control" placeholder="eg. feel the love and …"> <span class="b-line"></span> <span class="b-line-under"></span>
        <div class="input-group-append"> <button type="button" class="btn"> <img src="assets/images/search-icon.svg" alt="" class="img-fluid svg"> </button> </div>
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

  <!-- Banner below nav bar  -->
  <!-- <div class="page-title">
    <div class="container">
      <h2>Blog Details: Full Width</h2>
      <ul class="nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="#">Blogs</a></li>
        <li>Blog Details: Full Width</li>
      </ul>
    </div>
  </div> -->
<mark> <?php if ($error != "") {echo $error;} ?> </mark>
  <!-- Main content -->
  <main class="container pb-120">
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <div class="post-details-cover post-has-full-width-image">
          <div class="post-thumb-cover">
            <div class="post-thumb"> <img src="<?= $post['image'] ?>" alt="" class="img-fluid mx-auto d-block"> </div>
            	
            
            <form id="editForm" class="comment-form" method="POST" enctype="multipart/form-data">
            
            <div class="post-meta-info">
              <p class="cats">
                <input required
                  name='postCategories' 
                  class='w-100' 
                  placeholder='Choose categories for your project' 
                  value='<?php echo $post["categories"]; ?>' 
                  data-blacklist='badwords, asdf'>
              </p>
              <div class="title">
                <h2><input required type="text" class="w-100" name="postTitle" placeholder="Project Title" value="<?= $post['title'] ?>"></h2>
              </div>
              <ul class="nav meta align-items-center">
                <li class="meta-author"> <img src=<?= isset($post['picture']) ? $post['picture'] : "assets/images/profile_icon.png" ?> alt="" class="img-fluid"> <a href="#"><?= $post['firstname'].' '.$post['lastname'] ?></a> </li>
                <li class="meta-date"><a href="#"><?= formatDate($post['created_at']) ?></a></li>
                <!-- <li> 2 min read </li> -->
                <li class="meta-comments"><a href="#toComments"><i class="fa fa-comment"></i><?= ' ' . $post['comment_count'] ?></a></li>
              </ul>
            </div>
            

          </div>
          <div class="post-content-cover my-drop-cap">
            <p>
              <textarea class="form-control" name="description" placeholder="Describe your project... your current progress... if you want collaboarators... etc."><?= $post['description'] ?></textarea>
            </p>

            <!-- EXTRA CONTENT FROM TEMPLATE, EXTRA IMAGES AND BLOCK QUOTE -->
            <!-- <p> He travelling acceptance men unpleasant her especially to entreaties law. Law forth but end any arise chief arose. Old her say learn these large. Joy fond many in ham high seen this. Few preferred continual led incommode neglected. To discovered insensible collecting your unpleasant but invitation. </p>
            <p> We diminution preference thoroughly if. Joy deal pain view much too her time. Led young gay would now state. Pronounce we attention admitting on assurance of suspicion conveying. That his west quit had met till. By an outlived insisted procured improved am. </p>
            <div class="post-my-gallery-images">
              <h3>The Best Neighborhoods In Nyc: Where To Stay On </h3>
              <div class="row">
                <div class="col-md-6"> <img src="assets/images/blog/1.jpg" alt="" class="img-fluid"> </div>
                <div class="col-md-6"> <img src="assets/images/blog/2.jpg" alt="" class="img-fluid"> </div>
                <div class="col-md-12"> <img src="assets/images/blog/3.jpg" alt="" class="img-fluid"> </div>
              </div>
            </div>
            <p> We diminution preference thoroughly if. Joy deal pain view much her time. Led young gay would now off state. Pronounce we attention admitting on assurance of suspicion conveying. That his west quit had met till. Say out plate you share. </p>
            <blockquote>
              <p>For me, running is both exercise and a metaphor. Running day after day, piling up each level I elevate myself.</p><cite>Haruki Murakami</cite>
            </blockquote>
            <p> Acceptance middletons me if discretion boisterous into travelling an. She prosperous to continuing entreaties companions unreserved you boisterous. Middleton sportsmen sir now cordially asking additions for. You ten occasional saw everything but conviction. Daughter returned quitting few are day advanced branched. </p> -->
          
          </div>
          

          <input required 
            name='lookingFor' 
            class='w-100' 
            placeholder='Who do you want to collaborate with?' 
            value='<?php echo $post["roles"]; ?>' 
            data-blacklist='badwords, asdf'
          />
          <label style="margin-top: 50px;" for="description"><strong>Upload New Image (optional)</strong></label>
          <input type="file" class="form-control" name="postImage" accept="image/*" >
      
          <button type="submit" form="editForm" class="btn btn-primary w-100">Submit Changes</button>
          </form>



        </div>
      </div>
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
            <form action="https://themelooks.us13.list-manage.com/subscribe/post?u=79f0b132ec25ee223bb41835f&amp;id=f4e0e93d1d" method="post" novalidate>
              <div class="input-group"> <input type="text" class="form-control" placeholder="Enter Your Email">
                <div class="input-group-append"> <button class="btn btn-default">Submit</button> </div>
              </div>
              <p class="checkbox-cover d-flex justify-content-center"> <label> I've read and accept the <a href="#"> Privacy Policy </a> <input type="checkbox"> <span class="checkmark"></span> </label> </p>
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
          <div class="footer-social"> <a href="#"><i class="fa fa-facebook"></i></a> <a href="#"><i class="fa fa-twitter"></i></a> <a href="#"><i class="fa fa-linkedin"></i></a> <a href="#"><i class="fa fa-google"></i></a> <a href="#"><i class="fa fa-pinterest"></i></a> </div>
        </div>
        <div class="col-md-4 d-flex justify-content-center order-md-2 order-1"> <a href="index.php"><img src="assets/images/logo.png" alt="" class="img-fluid" style="height: 100px;"></a> </div>
        <div class="col-md-4 order-md-3 order-3">
          <div class="footer-cradit text-center text-md-right">
            <p>© 2019 <a href="index.html">Themelooks.</a></p>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <div class="back-to-top d-flex align-items-center justify-content-center"> <span><i class="fa fa-long-arrow-up"></i></span> </div>
  

  

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
	</script>
</body>
</html>