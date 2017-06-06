<?php
// bootstrap
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

// If not logged in, redirect to login page
if (!$user->isLoggedIn()) {
    header('location: /admin/login.php');
    die();
}

$page = 'admin-home';



$articles = $article->getArticles(999);

include(TEMPLATES_PATH . '/_header.php')
?>

<div class="container container-admin-index">

    <div class="row">
        <div class="col-md-9 col-md-offset-3">
            <h1>Admin panel</h1>
            <br>
        </div>
    </div>

    <div class="row">
        <!-- side navigation -->
        <?php include(TEMPLATES_PATH . '/admin/_side-nav.php'); ?>
    </div>


</div>


<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
