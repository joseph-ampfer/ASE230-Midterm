<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // Report all errors


$error = "";

//if the post request sent by form has some values
//then check if the user has entered email or not
if (count($_POST) > 0) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        //we need line count to assign the user an id which is the number of users we have 
        //so that we can save that as session id of the user
        require_once('db.php');

        // Clean inputs
        $email = strtolower(trim($_POST['email']));
        $password = password_hash($password, PASSWORD_DEFAULT);
        $firstname = trim($_POST['firstName']);
        $lastname = trim($_POST['lastName']);

        try {
            // Begin the transaction
            $db->beginTransaction();

            // Check if email already exists
            $checkStmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = :email"); /** @var PDOStatement $checkStmt */
            $checkStmt->execute([':email' => $email]);
            $emailExists = $checkStmt->fetchColumn();
            if ($emailExists) {
                throw new InvalidArgumentException("The email adress is already in use.");
            }

            // Execute your database operations
            $stmt1 = $db->prepare("INSERT INTO users (email, password_hash, firstname, lastname) VALUES (:name, :password, :firstname, :lastname)"); /** @var PDOStatement $stmt1 */
            $stmt1->execute(['name' => $email, 'password' => $password, 'firstname' => $firstname, 'lastname' => $lastname]);
            
            $_SESSION['email'] = $email;
            $_SESSION['ID'] = $db->lastInsertId();
            $_SESSION['isAdmin'] = false;
            // Commit the transaction
            $db->commit();


            header("Location: index.php");

        } catch (Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            // Handle the error (log it, display an error message, etc.)
            echo "Transaction failed: " . $e->getMessage();
            $error = $e->getMessage();
        }

    } else
        echo 'Email and password are missing';
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500%7CSpectral:400,400i,500,600,700"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>

<body>
    <header class="header">
        <div class="header-fixed is-sticky">
            <div class="container-fluid pl-120 pr-120 position-relative">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-3 col-md-4 col-6">
                        <div class="logo"> <a href="#"><img src="assets/images/logo.png" alt="" class="img-fluid"
                                    style="height: 100px;"></a>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-6 d-flex justify-content-end position-static">
                        <div class="nav-menu-cover">
                            <ul class="nav nav-menu">
                                <li><a href="index.php">Home</a></li>
                                <li><a href="about.html">About</a></li>
                                <li><a href="contact.html">Contact</a></li>
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
    <?php if (strlen($error) > 0) { ?>
        <div class="error-message"><?= $error ?></div>
    <?php } ?>
    <div class="container d-flex justify-content-center p-3">
        <form id="authForm" method="POST"
            class="d-flex flex-column justify-content-center w-50 h-100 p-4 shadow border-0">
            <h1 class="mb-3">Sign up</h1>
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" name="firstName" class="form-control " id="firstNameInput" required>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" name="lastName" class="form-control " id="lastNameInput" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="emailInput" aria-describedby="emailHelp"
                    required>
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" id="passwordInput" required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">See</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
    </div>
    <div class="container d-flex justify-content-center">
        <div>Already have an account?</div>
    </div>
    <div class="container d-flex justify-content-center">
        <a href="login.php" class="btn btn-secondary">Sign in</a>
    </div>
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
                            src="assets/images/logo.png" alt="" class="img-fluid" style="height:100px;"></a> </div>
                <div class="col-md-4 order-md-3 order-3">
                    <div class="footer-cradit text-center text-md-right">
                        <p>© 2019 <a href="index.html">Themelooks.</a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="scripts/customscripts.js"></script>
</body>

</html>