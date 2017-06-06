<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

include(TEMPLATES_PATH . '/_header.php')
?>

<div class="container container-admin-login">

    <div class="row">
        <div class="col-md-4 col-md-offset-3">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Login to admin area</h3>
                </div>
                <div class="panel-body">
                    <!-- login form -->
                    <form action="" method="post">
                        <!-- <p>Username:</p> -->
                        <input type="text" class="form-control" name="username" placeholder="Username">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <button type="submit" class="btn btn-primary btn-block" name="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
