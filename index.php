<?php
session_start();
// print_r($_SESSION);
require_once('scripts/scripts.php');
$isLoggedIn = false;
if (isset($_SESSION['email'])) {
    $isLoggedIn = true;
}
//Read the json file that has posts
$jsonData = file_get_contents('data/posts.json');
//Decode the json data 
$posts = json_decode($jsonData, true);
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
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/plugins/animate/animate.min.css">
    <link rel="stylesheet" href="assets/plugins/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/plugins/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/custom.css">
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
            <div class="input-group"> <input type="text" class="form-control" placeholder="eg. feel the love and …">
                <span class="b-line"></span> <span class="b-line-under"></span>
                <div class="input-group-append"> <button type="button" class="btn"> <img
                            src="assets/images/search-icon.svg" alt="" class="img-fluid svg"> </button> </div>
            </div>
        </form>
    </div>
    <header class="header">
        <div class="header-fixed" style="background-color:#fcfcfc">
            <div class="container-fluid pl-120 pr-120 position-relative">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="logo"> <a href="#"><img src="assets/images/logo.png" alt="" class="img-fluid"></a>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-6 d-flex align-items-center justify-content-end position-static">
                        <div class="nav-menu-cover">
                            <ul class="nav nav-menu align-items-center">
                                <li><a href="index.php">Home</a></li>
                                <li><a href="about.php">About</a></li>
                                <li><a href="contact.php">Contact</a></li>
                                <?php
                                echo $isLoggedIn ?
                                    '<li class="dropdown">
                                    <!-- User image as the dropdown trigger with inline styles -->
                                    <img src="assets/images/blog/author.jpg"
                                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; cursor: pointer;"
                                        class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false" alt="User Avatar">
                                
                                    <!-- Dropdown menu -->
                                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                        <li><a class="dropdown-item" href="#">Profile</a></li>
                                        <li><a class="dropdown-item" href="#">Settings</a></li>
                                        <li><a class="dropdown-item" href="#">Help</a></li>
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
                                <li class="search-toggle-open"> <img src="assets/images/search-icon.svg" alt=""
                                        class="img-fluid svg"> </li>
                                <li class="search-toggle-close hide"> <img src="assets/images/close.svg" alt=""
                                        class="img-fluid"> </li>
                                <li class="nav-menu-toggle"> <img src="assets/images/menu-toggler.svg" alt=""
                                        class="img-fluid svg"> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Post Modal Trigger -->
    <?php
    if ($isLoggedIn) {
        echo '
       <div class="container  mt-5" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style = "cursor: pointer; ">
           <div class="d-flex  justify-content-end">
               <div class="d-flex justify-content-between rounded-pill h-25 w-25 align-items-center p-3 shadow-lg rounded cursor-pointer bg-light hover:bg-gray-200">
                   <img src="assets/images/blog/author.jpg" alt="User Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; cursor: pointer;" />
                   <div class="ml-3 text-secondary">
                       What\'s on your mind?
                   </div>
                   <span>
                   <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                        <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z"/>
                    </svg>
                   </span>
               </div>
           </div>
       </div>';
    }
    ;
    ?>


    <!-- Post Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title " id="staticBackdropLabel">Create new post</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="grid gap-3 modal-body p-3">
                    <textarea class="form-control" style="background-color: whitesmoke" rows="4"
                        placeholder="Share your thoughts..." required></textarea>
                    <button><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-images" viewBox="0 0 16 16">
                            <path d="M4.502 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                            <path
                                d="M14.002 13a2 2 0 0 1-2 2h-10a2 2 0 0 1-2-2V5A2 2 0 0 1 2 3a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-1.998 2M14 2H4a1 1 0 0 0-1 1h9.002a2 2 0 0 1 2 2v7A1 1 0 0 0 15 11V3a1 1 0 0 0-1-1M2.002 4a1 1 0 0 0-1 1v8l2.646-2.354a.5.5 0 0 1 .63-.062l2.66 1.773 3.71-3.71a.5.5 0 0 1 .577-.094l1.777 1.947V5a1 1 0 0 0-1-1z" />
                        </svg>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-danger text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-success text-white">Post</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container pt-120 pb-90">
        <div class="row">
            <div class="col-md-6">
                <div class="post-default post-has-bg-img">
                    <div class="post-thumb"> <a href="blog-details.html">
                            <div data-bg-img="assets/images/blog/1.jpg"></div>
                        </a> </div>
                    <div class="post-data">
                        <div class="cats"><a href="category-result.html">Love</a></div>
                        <div class="title">
                            <h2><a href="blog-details.html">A Funny Thing That Happens In Relationships</a></h2>
                        </div>
                        <ul class="nav meta align-items-center">
                            <li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid">
                                <a href="#">Alex Garry</a>
                            </li>
                            <li class="meta-date"><a href="#">2 Feb 2019</a></li>
                            <li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="post-default post-has-bg-img">
                    <div class="post-thumb"> <a href="blog-details.html">
                            <div data-bg-img="assets/images/blog/2.jpg"></div>
                        </a> </div>
                    <div class="post-data">
                        <div class="cats"><a href="category-result.html">Fashion</a></div>
                        <div class="title">
                            <h2><a href="blog-details.html">The One Thing I Do When Fashion Come Over</a></h2>
                        </div>
                        <ul class="nav meta align-items-center">
                            <li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid">
                                <a href="#">Alex Garry</a>
                            </li>
                            <li class="meta-date"><a href="#">2 Feb 2019</a></li>
                            <li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="post-default post-has-bg-img">
                    <div class="post-thumb"> <a href="blog-details.html">
                            <div data-bg-img="assets/images/blog/3.jpg"></div>
                        </a> </div>
                    <div class="post-data">
                        <div class="cats"><a href="category-result.html">Travel</a></div>
                        <div class="title">
                            <h2><a href="blog-details.html">Summer Adventure Essentials From Backcountry</a></h2>
                        </div>
                        <ul class="nav meta align-items-center">
                            <li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid">
                                <a href="#">Alex Garry</a>
                            </li>
                            <li class="meta-date"><a href="#">2 Feb 2019</a></li>
                            <li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="post-default post-has-bg-img">
                    <div class="post-thumb"> <a href="blog-details.html">
                            <div data-bg-img="assets/images/blog/4.jpg"></div>
                        </a> </div>
                    <div class="post-data">
                        <div class="cats"><a href="category-result.html">Adventure</a></div>
                        <div class="title">
                            <h2><a href="blog-details.html">Top Things To Look For When Choosing A Safari Lodge</a></h2>
                        </div>
                        <ul class="nav meta align-items-center">
                            <li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid">
                                <a href="#">Alex Garry</a>
                            </li>
                            <li class="meta-date"><a href="#">2 Feb 2019</a></li>
                            <li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="post-default post-has-bg-img">
                    <div class="post-thumb"> <a href="blog-details.html">
                            <div data-bg-img="assets/images/blog/5.jpg"></div>
                        </a> </div>
                    <div class="post-data">
                        <div class="cats"><a href="category-result.html">Sports</a></div>
                        <div class="title">
                            <h2><a href="blog-details.html">Blaak Attack Earns Boels First 2019 Victory</a></h2>
                        </div>
                        <ul class="nav meta align-items-center">
                            <li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid">
                                <a href="#">Alex Garry</a>
                            </li>
                            <li class="meta-date"><a href="#">2 Feb 2019</a></li>
                            <li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="post-default post-has-bg-img">
                    <div class="post-thumb"> <a href="blog-details.html">
                            <div data-bg-img="assets/images/blog/6.jpg"></div>
                        </a> </div>
                    <div class="post-data">
                        <div class="cats"><a href="category-result.html">Food</a></div>
                        <div class="title">
                            <h2><a href="blog-details.html">Five Important Facts Should Know About Recipe</a></h2>
                        </div>
                        <ul class="nav meta align-items-center">
                            <li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid">
                                <a href="#">Alex Garry</a>
                            </li>
                            <li class="meta-date"><a href="#">2 Feb 2019</a></li>
                            <li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="post-default post-has-bg-img">
                    <div class="post-thumb"> <a href="blog-details.html">
                            <div data-bg-img="assets/images/blog/7.jpg"></div>
                        </a> </div>
                    <div class="post-data">
                        <div class="cats"><a href="category-result.html">Lifestyle</a></div>
                        <div class="title">
                            <h2><a href="blog-details.html">Great Britain's Winter Olympics Athletes Rated And
                                    Slated</a></h2>
                        </div>
                        <ul class="nav meta align-items-center">
                            <li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid">
                                <a href="#">Alex Garry</a>
                            </li>
                            <li class="meta-date"><a href="#">2 Feb 2019</a></li>
                            <li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="post-default post-has-bg-img">
                    <div class="post-thumb"> <a href="blog-details.html">
                            <div data-bg-img="assets/images/blog/8.jpg"></div>
                        </a> </div>
                    <div class="post-data">
                        <div class="cats"><a href="category-result.html">Technology</a></div>
                        <div class="title">
                            <h2><a href="blog-details.html">Apple Admits To Macbook And Macbook Pro</a></h2>
                        </div>
                        <ul class="nav meta align-items-center">
                            <li class="meta-author"> <img src="assets/images/blog/author.jpg" alt="" class="img-fluid">
                                <a href="#">Alex Garry</a>
                            </li>
                            <li class="meta-date"><a href="#">2 Feb 2019</a></li>
                            <li class="meta-comments"><a href="#"><i class="fa fa-comment"></i> 2</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="post-pagination d-flex justify-content-center"> <span class="current">1</span> <a href="#">2</a> <a
                href="#">3</a> <a href="#"><i class="fa fa-angle-right"></i></a> </div>
    </div>
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
                <div class="col-md-4 d-flex justify-content-center order-md-2 order-1"> <a href="index.html"><img
                            src="assets/images/logo.png" alt="" class="img-fluid"></a> </div>
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
    <script src="assets/plugins/owl-carousel/owl.carousel.min.js"></script>
    <script src="assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>