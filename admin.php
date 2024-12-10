<?php
require_once('Auth.php');
require_once('scripts/scripts.php');

$isLoggedIn = Auth::isLoggedIn();
$showAdminPage = Auth::isAdmin();

if (!$showAdminPage) {
    header("Location: index.php");
}
require_once("./db.php");

if ($isLoggedIn) {
	$userInfo = getUserInfo($db, $_SESSION['ID']);
}

$get_users_query = $db->query('SELECT * FROM users'); /** @var PDOStatement $get_users_query */
$users = $get_users_query->fetchAll();

// echo '<pre>';
// print_r($users);
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>U Collab</title>
	
	<link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">
	<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500%7CSpectral:400,400i,500,600,700"
		rel="stylesheet">
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
    <div class="container mt-5">
        <!-- Show this is the user is deleted successfully -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?= $_GET['message'] ?></div>
        <?php endif; ?>
        <!-- Show error if we can't delete the user for some reason -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= $_GET['error'] ?></div>
        <?php endif; ?>

        <h1 class="mb-4">Users</h1>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //Looping the users array that has list of all users
                    /** @var array $users */
                    foreach ($users as $index => $user) { ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= $user['firstname'] ?></td>
                            <td><?= $user['lastname'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td>
                                <a href="profile.php?id=<?= $user['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                <!-- Delete button triggers the specific modal -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteUserModal<?= $user['id'] ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>

                        <!-- Modal for this specific user 
                         I didn't know if we can create a function with a modal in it and pass
                         it the user id and then navigate from there to the deleteUser.php page
                         and then delete the user there.
                         -->
                        <div class="modal fade" id="deleteUserModal<?= $user['id'] ?>" tabindex="-1"
                            aria-labelledby="deleteUserModalLabel<?= $user['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete <?= $user['firstname'] ?>     <?= $user['lastname'] ?>?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <!-- Delete button submits a form I am handling a post request for now button
                                         we can come up with a different idea later-->
                                        <form action="deleteUser.php" method="POST">
                                            <!-- The user's id is passed in the input hopefully it will avoid injection -->
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>"> 
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </tbody>
            </table>
        </div>

    </div>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>