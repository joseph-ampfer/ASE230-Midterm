<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-Compatible" content="IE=edge">
    <title>Contact Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500%7CSpectral:400,400i,500,600,700"
        rel="stylesheet">

    <!-- Include Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/plugins/animate/animate.min.css">
    <link rel="stylesheet" href="assets/plugins/owl-carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/plugins/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>

<body>
    <!-- Navbar from home page -->
    <header class="header">
        <div class="header-fixed" style="background-color:#fcfcfc">
            <div class="container-fluid pl-120 pr-120 position-relative">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="logo"> <a href="#"><img src="assets/images/logo.png" alt="" class="img-fluid"></a></div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-6 d-flex align-items-center justify-content-end position-static">
                        <div class="nav-menu-cover">
                            <ul class="nav nav-menu align-items-center">
                                <li><a href="index.php">Home</a></li>
                                <li><a href="about.php">About</a></li>
                                <li><a href="contact.php" class="active">Contact</a></li>
                                <li><a href="login.php">Log in</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contact Form Section -->
    <section class="contact-form-section py-5">
        <div class="container">
            <h1 class="text-center mb-5">Get in Touch</h1>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="send-message.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-dark text-white text-center">
        <div class="container">
            <h2>Ready to Start Collaborating?</h2>
            <p>Join U Collab today and connect with like-minded creators.</p>
            <a href="signup.php" class="custom-signup-btn">Sign Up Now</a>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
