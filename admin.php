<?php
require_once("./db.php");
$get_users_query = $db->query('SELECT * FROM users');
$users = $get_users_query->fetchAll();
echo '<pre>';
// print_r($users);
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
    <div class="container ">
        <h1 class="mb-4">Users</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Handle</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //Looping the users array that has list of all users
                foreach ($users as $index => $user) { ?>
                    <tr>
                        <th scope='row'><?php echo $index + 1; ?></th>
                        <td><?= $user['firstname'] ?></td>
                        <td><?= $user['lastname'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td>
                            <a href="edit.php?id= <?= $user['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>