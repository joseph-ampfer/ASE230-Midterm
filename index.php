<?php

require_once('scripts/scripts.php');
$posts = readJsonData('data/posts.json');




?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>U Collab</title>
	<link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">
	<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500%7CSpectral:400,400i,500,600,700" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
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
		<div class="header-fixed">
			<div class="container-fluid pl-120 pr-120 position-relative">
				<div class="row d-flex align-items-center">
					<div class="col-lg-3 col-md-4 col-6">
						<div class="logo"> <a href="#"><img src="assets/images/logo.png" alt="" class="img-fluid"></a> </div>
					</div>
					<div class="col-lg-9 col-md-8 col-6 d-flex justify-content-end position-static">
						<div class="nav-menu-cover">
							<ul class="nav nav-menu">
								<li><a href="index.html">Home</a></li>
								<li><a href="about.html">About</a></li>
								<li class="menu-item-has-children"><a href="#">Blog</a>
									<ul class="sub-menu">
										<li class="menu-item-has-children"><a href="#">Blog List</a>
											<ul class="sub-menu">
												<li><a href="blog-list-sidebar.html">List Sidebar</a></li>
												<li><a href="blog-list-full-width.html">List Full Width</a></li>
												<li><a href="blog-grid-sidebar.html">Grid Sidebar</a></li>
												<li><a href="blog-grid-2-col.html">Grid v2</a></li>
												<li><a href="blog-grid-3-col.html">Grid v3</a></li>
												<li><a href="blog-overlay.html">Blog Overlay</a></li>
												<li><a href="blog-card-v1.html">Blog Card v1</a></li>
												<li><a href="blog-card-v2.html">Blog Card v2</a></li>
											</ul>
										</li>
										<li class="menu-item-has-children"><a href="#">Blog Details</a>
											<ul class="sub-menu">
												<li><a href="details-full-width.php">Default Style</a></li>
												<li><a href="blog-details-full-width.html">Full Width</a></li>
												<li><a href="blog-details-video.html">Video Post</a></li>
												<li><a href="blog-details-slide.html">Slide Post</a></li>
												<li><a href="blog-details-audio.html">Audio Post</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li class="menu-item-has-children"><a href="#">Pages </a>
									<ul class="sub-menu">
										<li><a href="categories.html">Categories</a></li>
										<li><a href="search-result.html">Search Results</a></li>
										<li><a href="404.html">404 Error</a></li>
									</ul>
								</li>
								<li><a href="contact.html">Contact</a></li>
							</ul>
						</div>
						<div class="mobile-menu-cover">
							<ul class="nav mobile-nav-menu">
								<li class="search-toggle-open"> <img src="assets/images/search-icon.svg" alt="" class="img-fluid svg"> </li>
								<li class="search-toggle-close hide"> <img src="assets/images/close.svg" alt="" class="img-fluid"> </li>
								<li class="nav-menu-toggle"> <img src="assets/images/menu-toggler.svg" alt="" class="img-fluid svg"> </li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header>

	<!-- Banner below nav bar -->
	<div class="page-title">
		<div class="container">
			<h2>Blog Overlay</h2>
			<ul class="nav">
				<li><a href="index.html">Home</a></li>
				<li><a href="#">Blog</a></li>
				<li>Blog Overlay</li>
			</ul>
		</div>
	</div>

	<!-- Main content -->
	<main class="container pt-120 pb-90">
		<div class="row">

			<?php foreach ($posts as $key => $post) {
			?>
				<div class="col-md-6">
					<div class="post-default post-has-bg-img">
						<div class="post-thumb">
							<a href="details-full-width.php">
								<div data-bg-img=<?= $post['postImage'] ?>></div>
							</a>
						</div>
						<div class="post-data">
							<div class="cats"><a href="category-result.html"><?= $post['postTag'] ?></a></div>
							<div class="title">
								<h2><a href="details-full-width.php"><?= $post['postTitle'] ?></a></h2>
							</div>
							<ul class="nav meta align-items-center">
								<li class="meta-author"> <img src=<?= $post['authorPic'] ?> alt="" class="img-fluid"> <a href="#"><?= $post['authorName'] ?></a> </li>
								<li class="meta-date"><a href="#"><?= formatDate($post['postTime']) ?></a></li>
								<li class="meta-comments"><a href="#"><i class="fa fa-comment"></i><?= ' ' . count($post['comments']) ?></a></li>
								<li class="meta-likes"><a href="#"><i class="fa fa-heart"></i><?= ' 6' ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			<?php } ?>

			<!-- Post 1 -->
			<div class="col-md-6">
				<div class="post-default post-has-bg-img">
					<div class="post-thumb">
						<a href="details-full-width.php">
							<div data-bg-img="assets/images/blog/1.jpg"></div>
						</a>
					</div>
					<div class="post-data">
						<div class="cats"><a href="category-result.html">Love</a></div>
						<div class="title">
							<h2><a href="details-full-width.php">A Funny Thing That Happens In Relationships</a></h2>
						</div>
						<ul class="nav meta align-items-center">
							<li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid"> <a href="#">Alex Garry</a> </li>
							<li class="meta-date"><a href="#">2 Feb 2019</a></li>
							<li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
						</ul>
					</div>
				</div>
			</div>

			<!-- Post 2 -->
			<div class="col-md-6">
				<div class="post-default post-has-bg-img">
					<div class="post-thumb"> <a href="details-full-width.php">
							<div data-bg-img="assets/images/blog/2.jpg"></div>
						</a> </div>
					<div class="post-data">
						<div class="cats"><a href="category-result.html">Fashion</a></div>
						<div class="title">
							<h2><a href="details-full-width.php">The One Thing I Do When Fashion Come Over</a></h2>
						</div>
						<ul class="nav meta align-items-center">
							<li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid"> <a href="#">Alex Garry</a> </li>
							<li class="meta-date"><a href="#">2 Feb 2019</a></li>
							<li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="post-default post-has-bg-img">
					<div class="post-thumb"> <a href="details-full-width.php">
							<div data-bg-img="assets/images/blog/3.jpg"></div>
						</a> </div>
					<div class="post-data">
						<div class="cats"><a href="category-result.html">Travel</a></div>
						<div class="title">
							<h2><a href="details-full-width.php">Summer Adventure Essentials From Backcountry</a></h2>
						</div>
						<ul class="nav meta align-items-center">
							<li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid"> <a href="#">Alex Garry</a> </li>
							<li class="meta-date"><a href="#">2 Feb 2019</a></li>
							<li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="post-default post-has-bg-img">
					<div class="post-thumb"> <a href="details-full-width.php">
							<div data-bg-img="assets/images/blog/4.jpg"></div>
						</a> </div>
					<div class="post-data">
						<div class="cats"><a href="category-result.html">Adventure</a></div>
						<div class="title">
							<h2><a href="details-full-width.php">Top Things To Look For When Choosing A Safari Lodge</a></h2>
						</div>
						<ul class="nav meta align-items-center">
							<li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid"> <a href="#">Alex Garry</a> </li>
							<li class="meta-date"><a href="#">2 Feb 2019</a></li>
							<li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="post-default post-has-bg-img">
					<div class="post-thumb"> <a href="details-full-width.php">
							<div data-bg-img="assets/images/blog/5.jpg"></div>
						</a> </div>
					<div class="post-data">
						<div class="cats"><a href="category-result.html">Sports</a></div>
						<div class="title">
							<h2><a href="details-full-width.php">Blaak Attack Earns Boels First 2019 Victory</a></h2>
						</div>
						<ul class="nav meta align-items-center">
							<li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid"> <a href="#">Alex Garry</a> </li>
							<li class="meta-date"><a href="#">2 Feb 2019</a></li>
							<li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="post-default post-has-bg-img">
					<div class="post-thumb"> <a href="details-full-width.php">
							<div data-bg-img="assets/images/blog/6.jpg"></div>
						</a> </div>
					<div class="post-data">
						<div class="cats"><a href="category-result.html">Food</a></div>
						<div class="title">
							<h2><a href="details-full-width.php">Five Important Facts Should Know About Recipe</a></h2>
						</div>
						<ul class="nav meta align-items-center">
							<li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid"> <a href="#">Alex Garry</a> </li>
							<li class="meta-date"><a href="#">2 Feb 2019</a></li>
							<li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="post-default post-has-bg-img">
					<div class="post-thumb"> <a href="details-full-width.php">
							<div data-bg-img="assets/images/blog/7.jpg"></div>
						</a> </div>
					<div class="post-data">
						<div class="cats"><a href="category-result.html">Lifestyle</a></div>
						<div class="title">
							<h2><a href="details-full-width.php">Great Britain's Winter Olympics Athletes Rated And Slated</a></h2>
						</div>
						<ul class="nav meta align-items-center">
							<li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid"> <a href="#">Alex Garry</a> </li>
							<li class="meta-date"><a href="#">2 Feb 2019</a></li>
							<li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="post-default post-has-bg-img">
					<div class="post-thumb"> <a href="details-full-width.php">
							<div data-bg-img="assets/images/blog/8.jpg"></div>
						</a> </div>
					<div class="post-data">
						<div class="cats"><a href="category-result.html">Technology</a></div>
						<div class="title">
							<h2><a href="details-full-width.php">Apple Admits To Macbook And Macbook Pro</a></h2>
						</div>
						<ul class="nav meta align-items-center">
							<li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid"> <a href="#">Alex Garry</a> </li>
							<li class="meta-date"><a href="#">2 Feb 2019</a></li>
							<li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
						</ul>
					</div>
				</div>
			</div>

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
				<div class="col-md-4 d-flex justify-content-center order-md-2 order-1"> <a href="index.html"><img src="assets/images/logo.png" alt="" class="img-fluid"></a> </div>
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
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<script src="assets/plugins/owl-carousel/owl.carousel.min.js"></script>
	<script src="assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
	<script src="assets/js/scripts.js"></script>
	<script src="assets/js/custom.js"></script>
</body>

</html>