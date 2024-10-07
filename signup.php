


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
        <form method="POST" class="card d-flex flex-column justify-content-center w-50 h-auto p-4 shadow border-0">
            <h1 class="mb-3">Sign up</h1>
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" name="firstName" class="form-control" id="firstNameInput" required>
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" name="lastName" class="form-control" id="lastNameInput" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" name = "email" class="form-control" id="emailInput" aria-describedby="emailHelp" required>
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="passwordInput" required>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" name="confirmPassword" class="form-control" id="confirmPasswordInput" required>
                <div id="passwordHelp" class="form-text">Passwords must match</div>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
    </div>

</html>