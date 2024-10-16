<?php
session_start();
require_once('scripts/scripts.php');
$isLoggedIn = false;
if (isset($_SESSION['email'])) {
	$isLoggedIn = true;
	$email = $_SESSION['email'];
	$username = getUserName($email);
}
$error = "";

// To post a comment, check if logged and comment there
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

			// Validate Mime type
			$detectedType = mime_content_type($_FILES['postImage']['tmp_name']);
			if (!in_array($detectedType, $allowedMimeTypes)) {
				$error = "Must upload an image (jpeg, jpg, png, gif)";
			} else {
				//===== ELSE procede with post upload ===	
				$fextension = pathinfo($_FILES['postImage']['name'], PATHINFO_EXTENSION);
				$time = time();
				$imagePath = './assets/images/blog/' . $time . '.' . $fextension;
				move_uploaded_file($_FILES['postImage']['tmp_name'], $imagePath);


				$data = $_POST;

				// Add time, likes, etc
				$data['postTime'] = date("Y-m-d H:i:s");
				$data['likes'] = 0;
				$data['comments'] = [];
				$data['authorName'] = $username;
				$data['email'] = $_SESSION['email'];
				$postCategories = json_decode($_POST['postCategories'], true);
				$lookingFor = json_decode($_POST['lookingFor'], true);

				$data['postCategories'] = array_map(function ($item) {
					return $item['value'];
				}, $postCategories);
				$data['lookingFor'] = array_map(function ($item) {
					return $item['value'];
				}, $lookingFor);
				$data['postImage'] = $imagePath;

				saveToJson('data/posts.json', $data);
			}

		}

	}
}


$posts = readJsonData('data/posts.json');
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
	<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500%7CSpectral:400,400i,500,600,700" rel="stylesheet">
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
	<div class="preloader">
		<div class="preload-img">
			<div class="spinnerBounce">
				<div class="double-bounce1"></div>
				<div class="double-bounce2"></div>
			</div>
		</div>
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
								<li><a href="contact.php"></a></li>
								<?php
								echo $isLoggedIn ?
									'<li class="dropdown">
                                    <!-- User image as the dropdown trigger with inline styles -->
                                    <img src="assets/images/blog/author.png"
                                        style="width: 50px; height: 5x0px; border-radius: 50%; object-fit: cover; cursor: pointer;"
                                        class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false" alt="User Avatar">
                                
                                    <!-- Dropdown menu -->
                                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
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

		<!-- Post Modal Trigger -->
		<?php
		if ($isLoggedIn) { ?>

			<div class="container  mt-5 mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal"
				style="cursor: pointer; ">
				<div class="d-flex  justify-content-end">
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
		;
		?>
		<mark><?php if ($error != "") {
			echo $error;
		} ?></mark><br>

		<div class="row">
			<!-- v2 -->
			<?php foreach ($posts as $key => $post) { ?>
				<div class="col-md-6">
					<div class="post-default post-has-bg-img">
						<div class="post-thumb">
							<a href="details-full-width.php?id=<?= $key ?>">
								<div data-bg-img=<?= $post['postImage'] ?>></div>
							</a>
						</div>
						<div class="post-data">
							<div class="cats">
								<?php foreach ($post['postCategories'] as $category) { ?>
									<a href=""><?= $category ?></a>
								<?php } ?>
							</div>
							<div class="title mb-1">
								<h2><a href="details-full-width.php?id=<?= $key ?>"><?= $post['postTitle'] ?></a></h2>
							</div>
							<p class="shortDescription mb-5 px-10">
								<?= !empty($post['description']) ? substr($post['description'], 0, 100) . '...' : '' ?>
							</p>
							<!-- Shortened project description -->
							<div>
								<p class="text-white/80">Looking for:</p>
								<div class="flex space-x-2 items-center justify-center">
									<?php foreach ($post['lookingFor'] as $cat) { ?>
										<span class="bg-white/10 p-2 text-white"><?= $cat ?></span>
									<?php } ?>
								</div>
							</div>
							<ul class="nav meta align-items-center absolute bottom-0 left-0 ml-5">
								<li class="meta-author flex items-center justify-center space-x-2">
									<img src="<?= isset($post['authorPic']) ? $post['authorPic'] : "assets/images/profile_icon.png" ?>" alt="" class="img-fluid">
									<a class="text-white/80" href="#"><?= $post['authorName'] ?></a>
								</li>
								<li class="meta-date"><a class="text-white/80" href="#"><?= formatDate($post['postTime']) ?></a></li>
								<li class="meta-comments"><a class="text-white/80" href="#"><i class="fa fa-comment text-white/80"></i> <?= count($post['comments']) ?></a></li>
								<li class="meta-likes"><a class="text-white/80" href="#"><i class="fa fa-heart text-white/80"></i> <?= $post['likes'] ?? 0 ?></a></li> <!-- Optional likes feature -->
							</ul>
							<!-- <div class="join-project">
								<a href="contact-owner.php?id=<?= $key ?>" class="btn btn-primary">Join Project</a>
							</div> -->
						</div>
					</div>
				</div>
			<?php } ?>

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
							<p class="checkbox-cover d-flex justify-content-center"> 
								<label> I've read and accept the <a href="#"> Privacy Policy </a> <input type="checkbox"> <span class="checkmark"></span> </label> 
							</p>
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
					<div class="footer-social"> 
						<a href="#"><i class="fa fa-facebook"></i></a>
						<a href="#"><i class="fa fa-twitter"></i></a> 
						<a href="#"><i class="fa fa-linkedin"></i></a> 
						<a href="#"><i class="fa fa-google"></i></a> 
						<a href="#"><i class="fa fa-pinterest"></i></a>
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