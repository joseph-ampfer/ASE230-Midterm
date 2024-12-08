<?php
session_start();
require_once('scripts/scripts.php');

$isLoggedIn = false;
$email = "";
if (isset($_SESSION['email'])) {
  $isLoggedIn = true;
  $email = $_SESSION['email'];

}

// Id for the post page
$postIndex = $_GET['id'];
$error="";
require_once('db.php');

// To post a comment, check if logged and comment there
if ($isLoggedIn && count($_POST) > 0) {
  if (isset($_POST['comment'][0])) {
    try { 
      newsaveComment($db, $postIndex, $_POST);
    } catch(Exception $e) { 
      $error = $e->getMessage(); 
    }
  }
}

// GET THE POST
try {

  // Get post
  $q = "
    SELECT title, description, user_id, image, p.created_at, firstname, lastname, picture
    FROM posts p
    LEFT JOIN users u ON p.user_id = u.id
    WHERE p.id = ? AND status = 'published'
  ";
  $stmt = $db->prepare($q); /** @var PDOStatement $stmt */
  $stmt->execute([$postIndex]);
  $post = $stmt->fetch();

  // Get post categories
  $q = "
    SELECT category 
    FROM post_categories
    WHERE post_id = ?
  ";
  $stmt = $db->prepare($q); /** @var PDOStatement $stmt */
  $stmt->execute([$postIndex]);
  $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);


  // Get post looking_for
  $q = "
    SELECT role 
    FROM looking_for
    WHERE post_id = ?
  ";
  $stmt = $db->prepare($q); /** @var PDOStatement $stmt */
  $stmt->execute([$postIndex]);
  $looking_for = $stmt->fetchAll(PDO::FETCH_COLUMN);


  // Get post comments
  $q = "
    SELECT c.id, post_id, user_id, parent_id, comment, c.created_at, firstname, lastname, picture
    FROM comments c 
    LEFT JOIN users u ON c.user_id = u.id 
    WHERE post_id = ?
    ORDER BY c.created_at DESC
  ";
  $stmt = $db->prepare($q); /** @var PDOStatement $stmt */
  $stmt->execute([$postIndex]);
  $comments = $stmt->fetchAll();

    // Get post comments
  $q = "
    SELECT COUNT(user_id) AS like_count
    FROM post_likes 
    WHERE post_id = ?
  ";
  $stmt = $db->prepare($q); /** @var PDOStatement $stmt */
  $stmt->execute([$postIndex]);
  $like_count = $stmt->fetchColumn();


} catch(Exception $e) {
  if ($db->inTransaction()){
    $db->rollBack();
  }
  // Handle the error (log it, display an error message, etc.)
  echo "Transaction failed: " . $e->getMessage();
  $error = $e->getMessage();
}


echo $error;
// Get page content
//$posts = readJsonData('./data/posts.json');
//$post = $posts[$postIndex];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BizBlog</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">
  <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500%7CSpectral:400,400i,500,600,700"
    rel="stylesheet">
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
      <div class="input-group"> <input type="text" class="form-control" placeholder="eg. feel the love and …"> <span
          class="b-line"></span> <span class="b-line-under"></span>
        <div class="input-group-append"> <button type="button" class="btn"> <img src="assets/images/search-icon.svg"
              alt="" class="img-fluid svg"> </button> </div>
      </div>
    </form>
  </div>
  <header class="header">
    <div class="header-fixed" style="background-color:#fcfcfc">
      <div class="container-fluid pl-120 pr-120 position-relative">
        <div class="row d-flex align-items-center">
          <div class="col-lg-3 col-md-4 col-6">
            <div class="logo"> <a href="#"><img src="assets/images/logo.png" alt="" class="img-fluid" style = "height:90px"></a>
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
                                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; cursor: pointer;"
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
                <li class="search-toggle-open"> <img src="assets/images/search-icon.svg" alt="" class="img-fluid svg">
                </li>
                <li class="search-toggle-close hide"> <img src="assets/images/close.svg" alt="" class="img-fluid"> </li>
                <li class="nav-menu-toggle"> <img src="assets/images/menu-toggler.svg" alt="" class="img-fluid svg">
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

  <!-- Main content -->
  <main class="container pb-120">
    <div class="row">
      <div class="col-md-10 offset-md-1">
        <div class="post-details-cover post-has-full-width-image">
          <div class="post-thumb-cover">
            <div class="post-thumb"> <img src="<?= $post['image'] ?>" alt="" class="img-fluid mx-auto d-block">
            </div>

            <div class="post-meta-info">
              <p class="cats">
                <?php   /** @var array $categories */
                foreach ($categories as $cat) { ?>
                  <a href="#"><?= $cat ?></a> <?php } ?>
              </p>
              <div class="title">
                <h2><?= $post['title'] ?></h2>
              </div>
              <ul class="nav meta align-items-center">
                <li class="meta-author"> <img src=<?= isset($post['picture']) ? $post['picture'] : "assets/images/profile_icon.png" ?> alt="" class="img-fluid"> <a
                    href="#"><?= $post['firstname'].' '.$post['lastname'] ?></a> </li>
                <li class="meta-date"><a href="#"><?= formatDate($post['created_at']) ?></a></li>
                <!-- <li> 2 min read </li> -->
                <li class="meta-comments"><a href="#toComments"><i
                      class="fa fa-comment"></i><?= ' ' . count($comments) ?></a></li>
                <li class="meta-likes"><a class="text-white/80" href="#"><i
											class="fa fa-heart text-white/80"></i> <?= $like_count ?? 0 ?></a></li>
              </ul>
            </div>
          </div>
          <div class="post-content-cover my-drop-cap">
            <p><?= nl2br($post['description'])  ?></p>

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
          <div class="post-all-tags">
            <?php   /** @var array $looking_for */ 
            foreach ($looking_for as $cat) { ?>
              <a href="#"><?= $cat ?></a>
            <?php } ?>
          </div>
          <!-- Comments -->
          <button id="toComments" class="btn btn-comment" type="button" data-toggle="collapse"
            data-target="#commentToggle" aria-expanded="false" aria-controls="commentToggle"> Hide Comments
            (<?= count($comments) ?>) </button>
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

              <?php   /** @var array $comments */
              foreach ($comments as $comment) { ?>
                <li class="single-comment-wrapper">
                  <div class="single-post-comment">
                    <div class="comment-author-image"> 
                      <img src="<?= $comment['picture'] ?>" alt="" class="img-fluid"> </div>
                    <div class="comment-content">
                      <div class="comment-author-name">
                        <h6><?= $comment['firstname'].' '.$comment['lastname'] ?></h6> <span>
                          <?= formatDate($comment['created_at']) . ' at ' . formatTime($comment['created_at']) ?> </span>
                      </div>
                      <p><?= nl2br($comment['comment']) ?></p><a href="#" class="reply-btn">Reply</a>
                    </div>
                  </div>
                </li>
              <?php } ?>


            </ul>
          </div>

          <!-- FORM Write a comment -->
          <?php if (isset($_SESSION['email'])) { ?>
            <div class="post-comment-form-cover">
              <h3>Write your comment</h3>
              <form class="comment-form" method="POST" action=<?= "details-full-width.php?id=" . $postIndex ?>>
                <div class="row">
                  <div class="col-md-12"> <textarea class="form-control" name="comment"
                      placeholder="Write your comment"></textarea> </div>
                  <div class="col-md-12"> <button class="btn btn-primary">Send Comment</button> </div>
                </div>
              </form>
            </div>
          <?php } else { ?>
            <div class="post-comment-form-cover">
              <h3>Want To Comment?</h3>
              <form class="comment-form" method="POST" action=<?= "login.php" ?>>
                <div class="row">
                  <div class="col-md-12"> <button class="btn btn-primary">Login First</button> </div>
                </div>
              </form>
            </div>
          <?php } ?>


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
            <form
              action="https://themelooks.us13.list-manage.com/subscribe/post?u=79f0b132ec25ee223bb41835f&amp;id=f4e0e93d1d"
              method="post" novalidate>
              <div class="input-group"> <input type="text" class="form-control" placeholder="Enter Your Email">
                <div class="input-group-append"> <button class="btn btn-default">Submit</button> </div>
              </div>
              <p class="checkbox-cover d-flex justify-content-center"> <label> I've read and accept the <a href="#">
                    Privacy Policy </a> <input type="checkbox"> <span class="checkmark"></span> </label> </p>
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
                class="fa fa-twitter"></i></a> <a href="#"><i class="fa fa-linkedin"></i></a> <a href="#"><i
                class="fa fa-google"></i></a> <a href="#"><i class="fa fa-pinterest"></i></a> </div>
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