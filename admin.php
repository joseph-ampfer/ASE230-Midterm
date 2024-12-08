<?php
session_start();
if (!$_SESSION['isAdmin']) {
    header("Location: index.php");
}
require_once("./db.php");
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
    <link rel="shortcut icon" type="image/png" href="assets/images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500%7CSpectral:400,400i,500,600,700"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container ">
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