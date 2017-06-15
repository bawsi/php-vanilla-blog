<?php
//bootstrap
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = '';

include(TEMPLATES_PATH . '/_header.php');
?>

<!-- Main content  -->
<div class="container container-404">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
            <br>
            <h1 style="font-size:120px;">404</h1>
            <h4>Page you requested could not be found.</h4>
            <br>

            <!-- search -->
            <form class="search" action="/search.php" method="get" style="margin:0 auto;">
                <div class="form-group" style="display: inline-block;">
                    <input type="text" name="s" class="form-control input-sm search-input" placeholder="Search" required="required" style="width: 170px;">
                </div>
                <button type="submit" class="btn btn-danger btn-sm search-btn">Search</button>
            </form>

        </div>
    </div>
</div>

<!-- footer -->
<?php include(TEMPLATES_PATH . '/_footer.php') ?>
