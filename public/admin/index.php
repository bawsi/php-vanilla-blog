<?php
// bootstrap and page variables
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'admin';
$page = 'admin-home';

// If not logged in, redirect to login page
$user->redirectIfNotLoggedIn();

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
