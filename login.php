<?php
session_start();
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // Report all errors
if (isset($_SESSION['email']))
    die('You are already sign in, no need to sign in.');
if (count($_POST) > 0) {
    if (isset($_POST['email'][0]) && isset($_POST['password'][0])) {
        // process information
        $index = 0;
        $fp = fopen(__DIR__ . '/data/users.csv', 'r');
        while (!feof($fp)) {
            $line = fgets($fp);
            if (strstr($line, '<?php die() ?>') || strlen($line) < 5)
                continue;
            $index++;
            $line = explode(';', trim($line));
            if ($line[0] == $_POST['email'] && password_verify($_POST['password'], $line[1])) {
                // Sign the user in
                //1. Save the user's data into the session
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['ID'] = $index;
                //Navigate the user to home page
                header("Location: index.php");
            }
        }
        fclose($fp);
        // The credentials are wrong
        if ($showForm)
            echo 'Your credentials are wrong';
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
</head>

<body>
    <div class="container d-flex justify-content-center p-3">
        <form class="card d-flex flex-column justify-content-center w-50 h-25 p-4 shadow border-0">
            <h1 class="mb-3">Log in</h1>
            <div class="d-flex align-items-center mx-auto mb-3">
                <div>Don't have an account yet?</div>
                <a href="signup.php">Sign up</a>
            </div>

            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" required>
                <a href="changePasswordPage" class="form-text">Forgot password?</a>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>