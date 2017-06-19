<?php
// bootstrap and page variables
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'admin';
$page = 'admin-users';

// If not logged in, redirect to login page
$user->redirectIfNotLoggedIn();

// Grabbing list of all users
$users = $user->getAllUsers();

include(TEMPLATES_PATH . '/_header.php');
?>

<!-- Main content -->
<div class="container container-new-article">

    <div class="row">
        <div class="col-md-9 col-md-offset-3">
            <h1>Manage users</h1>
            <br>
        </div>
    </div>

	<div class="row">
		<!-- Sidebar column-->
		<?php include(TEMPLATES_PATH . '/admin/_side-nav.php'); ?>

        <!-- users -->
		<div class="col-md-9">
            <table class="table table-bordered">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Total articles</th>
                    <th>Options</th>
                </tr>

                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td><?php echo 'TODO'; ?></td>
                        <td>
                            <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>

            <a class="btn btn-success" href="#"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add user</a>
            
        </div>
    </div>
</div>

<!-- footer -->
<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
