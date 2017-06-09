<?php
// bootstrap
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'login';

// If already logged in, redirect to admin panel
($user->isLoggedIn()) ? header('location: /admin') : '';

// If POST request, try to login with submitted data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['username']) && !empty($_POST['password'])) {
    if ($user->login($_POST['username'], $_POST['password'])) {
        header('location: /admin');
    } else {
        $_SESSION['error_messages'][] = 'Invalid username / password combination.';
    }
} else if (isset($_POST['submit'])) {
    $_SESSION['error_messages'][] = 'Both fields are required!';
}

include(TEMPLATES_PATH . '/_header.php')
?>

<div class="container container-admin-login">

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h1 class="text-center">Login to admin area</h1>
            <p class="text-center"><strong>Username:</strong> admin - <strong>Password:</strong> 123</p>
            <hr />
            <form class="login-form" action="" method="post">
                <!-- <p>Username:</p> -->
                <input type="text" class="form-control" name="username" placeholder="Username" required="required">
                <input type="password" class="form-control" name="password" placeholder="Password" required="required">
                <button type="submit" class="btn btn-success btn-block" name="submit">Login</button>
            </form>

        </div>
    </div>

</div>


<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
