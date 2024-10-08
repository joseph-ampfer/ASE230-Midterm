<?php

require_once('./scripts/scripts.php');

// Index for the post page
$postIndex = $_GET['id'];

// Change to session logic !!!!!!
$isLoggedIn = true;
$username = "Joseph Ampfer";

// To post a comment, check if logged and comment there
if ($isLoggedIn && count($_POST) > 0) {
  if (isset($_POST['comment'][0])) {
    saveComment('data/posts.json', $postIndex, $_POST);
  }
}

// Get page content
$posts = readJsonData('./data/posts.json');
$post = $posts[$postIndex];



//echo '<pre>';
print_r($_POST);
//echo '</pre>';





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
                        <li><a href="blog-details.html">Default Style</a></li>
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

  <!-- Banner below nav bar  -->
  <div class="page-title">
    <div class="container">
      <h2>Blog Details: Full Width</h2>
      <ul class="nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="#">Blogs</a></li>
        <li>Blog Details: Full Width</li>
      </ul>
    </div>
  </div>

  <!-- Main content -->
  <main class="container pb-120">
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <div class="post-details-cover post-has-full-width-image">
          <div class="post-thumb-cover">
            <div class="post-thumb"> <img src="assets/images/blog/4.jpg" alt="" class="img-fluid"> </div>
            <div class="post-meta-info">
              <p class="cats">
                <?php foreach ($post['postCategories'] as $category) { ?>
                  <a href="#"><?= $category ?></a> <?php } ?>
              </p>
              <div class="title">
                <h2><?= $post['postTitle'] ?></h2>
              </div>
              <ul class="nav meta align-items-center">
                <li class="meta-author"> <img src=<?= $post['authorPic'] ?> alt="" class="img-fluid"> <a href="#"><?= $post['authorName'] ?></a> </li>
                <li class="meta-date"><a href="#"><?= formatDate($post['postTime']) ?></a></li>
                <!-- <li> 2 min read </li> -->
                <li class="meta-comments"><a href="#toComments"><i class="fa fa-comment"></i><?= ' ' . count($post['comments']) ?></a></li>
              </ul>
            </div>
          </div>
          <div class="post-content-cover my-drop-cap">
            <p><?= nl2br($post['description']) ?></p>

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
          <div class="post-all-tags"> <a href="#">Fashion</a> <a href="#">Art</a> <a href="#">Lifestyle</a> <a href="#">Love</a> <a href="#">Travel</a> <a href="#">Movie</a> <a href="#">Games</a> </div>

          <!-- Author box -->
          <div class="post-about-author-box">
            <div class="author-avatar"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid"> </div>
            <div class="author-desc">
              <h5> <a href="#"> Alex Garry </a> </h5>
              <div class="description"> On recommend tolerably my belonging or am. Mutual has cannot beauty indeed now sussex merely you. It possible no husbands jennings ye offended packages pleasant he. </div>
              <div class="social-icons"> <a href="#"><i class="fa fa-facebook"></i></a> <a href="#"><i class="fa fa-twitter"></i></a> <a href="#"><i class="fa fa-instagram"></i></a> <a href="#"><i class="fa fa-pinterest"></i></a> <a href="#"><i class="fa fa-linkedin"></i></a> </div>
            </div>
          </div>

          <!-- Comments -->
          <button id="toComments" class="btn btn-comment" type="button" data-toggle="collapse" data-target="#commentToggle" aria-expanded="false" aria-controls="commentToggle"> Hide Comments (<?= count($post['comments']) ?>) </button>
          <div class="collapse show" id="commentToggle">
            <ul class="post-all-comments">

              <!-- <li class="single-comment-wrapper">
                <div class="single-post-comment">
                  <div class="comment-author-image"> <img src="assets/images/blog/post/author-1.jpg" alt="" class="img-fluid"> </div>
                  <div class="comment-content">
                    <div class="comment-author-name">
                      <h6>Don Norman</h6> <span> 5 Jan 2019 at 6:40 pm </span>
                    </div>
                    <p>On recommend tolerably my belonging or am. Mutual has cannot beauty indeed now back sussex merely you. It possible no husbands jennings offended.</p><a href="#" class="reply-btn">Reply</a>
                  </div>
                </div>
                <ul class="children">
                  <li class="single-comment-wrapper">
                    <div class="single-post-comment">
                      <div class="comment-author-image"> <img src="assets/images/blog/post/author-1-1.jpg" alt="" class="img-fluid"> </div>
                      <div class="comment-content">
                        <div class="comment-author-name">
                          <h6>Helen Sharp</h6> <span> 5 Jan 2019 at 6:58 pm </span>
                        </div>
                        <p>On recommend tolerably my belonging or am. Mutual has cannot back beauty indeed now back sussex merely you. </p><a href="#" class="reply-btn">Reply</a>
                      </div>
                    </div>
                  </li>
                </ul>
              </li> -->

              <?php foreach ($post['comments'] as $comment) { ?>
                <li class="single-comment-wrapper">
                  <div class="single-post-comment">
                    <div class="comment-author-image"> <img src="assets/images/blog/post/author-2.jpg" alt="" class="img-fluid"> </div>
                    <div class="comment-content">
                      <div class="comment-author-name">
                        <h6><?= $comment['username'] ?></h6> <span> <?= formatDate($comment['time']) . ' at ' . formatTime($comment['time']) ?> </span>
                      </div>
                      <p><?= nl2br($comment['comment']) ?></p><a href="#" class="reply-btn">Reply</a>
                    </div>
                  </div>
                </li>
              <?php } ?>


            </ul>
          </div>

          <!-- FORM Write a comment -->
          <div class="post-comment-form-cover">
            <h3>Write your comment</h3>
            <form class="comment-form" method="POST" action=<?="details-full-width.php?id=".$postIndex ?>>
              <div class="row">
                <div class="col-md-6"> <input type="text" class="form-control" name="username" placeholder="Name"> </div>
                <div class="col-md-6"> <input type="text" class="form-control" name="email" placeholder="Email"> </div>
                <div class="col-md-12"> <textarea class="form-control" name="comment" placeholder="Write your comment"></textarea> </div>
                <input type="hidden"  />
                <div class="col-md-12"> <button class="btn btn-primary">Submit </button> </div>
              </div>
            </form>
          </div>



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

  <!-- Include Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script src="assets/plugins/owl-carousel/owl.carousel.min.js"></script>
  <script src="assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>
</body>

</html>