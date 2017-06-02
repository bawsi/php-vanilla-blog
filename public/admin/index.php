<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$page = 'admin-home';

$articles = $article->getArticles(999);

include(TEMPLATES_PATH . '/_header.php')
?>

<div class="container container-admin-index">

    <?php include(TEMPLATES_PATH . '/admin/_breadcrumbs.php'); ?>

    <div class="row">

        <!-- side navigation -->
        <?php include(TEMPLATES_PATH . '/admin/_side-nav.php'); ?>

        <h2>Admin panel home page</h2>
    </div>


</div>


<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
