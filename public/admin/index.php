<?php
// bootstrap and page variables
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'admin';
$page = 'admin-home';

// If not logged in, redirect to login page
$user->redirectIfNotLoggedIn();

$articles = $article->getArticles(999);

// Getting stats for panels
$stats = $article->getUserStats();

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

        <!-- main content -->
        <div class="col-md-9">
            <div class="row">

                <div class="col-md-4">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="panel-title">Your username</div>
                        </div>
                        <div class="panel-body text-center">
                            <h3><?php echo $stats['username']; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">Your role</div>
                        </div>
                        <div class="panel-body text-center">
                            <h3><?php echo $stats['role']; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="panel-title">Account registered on</div>
                        </div>
                        <div class="panel-body text-center">
                            <h3><?php echo date('d.m.Y', $stats['createdAt']); ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">Total articles by you</div>
                        </div>
                        <div class="panel-body text-center">
                            <h3><?php echo $stats['totalArticles']; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="panel-title">Your latest article published on</div>
                        </div>
                        <div class="panel-body text-center">
                            <h3><?php echo date('d.m.Y', $stats['latestArticleTime']); ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="panel-title">Your most active category</div>
                        </div>
                        <div class="panel-body text-center">
                            <h3><?php echo $stats['mostActiveCategory']['category_name']; ?></h3>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>


</div>


<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
</body>
</html>