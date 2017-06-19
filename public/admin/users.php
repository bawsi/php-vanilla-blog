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
                    <th>Latest article</th>
                    <th>Options</th>
                </tr>

                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <!-- TODO: Get total articles of user, and latest article date, and fill it in -->
                        <td><?php echo 'TODO'; ?></td>
                        <td><?php echo 'TODO'; ?></td>
                        <td>
                            <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a href="<?php echo '/admin/delete-user.php?id=' . $user['id']; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>

            <button class="btn btn-success" data-toggle="modal" data-target="#myModal">
                <i class="fa fa-plus-circle" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i>
                 Add user
            </button>

            <!-- Modal  -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add new user</h4>
                        </div>
                        <div class="modal-body">

                            <!-- new user form -->
                            <form action="/admin/new-user.php" method="post">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input required class="form-control" type="text" name="username" placeholder="Username" style="margin-bottom:10px;">
                                    <label for="password">Password</label>
                                    <input required class="form-control" type="password" name="password" placeholder="password" style="margin-bottom:10px;">
                                    <label for="role">User role</label>
                                    <select required class="form-control" name="role">
                                        <option value="writer">Writer</option>
                                        <option value="mod">Moderator</option>
                                    </select>
                                </div>
                                <hr>
                                <div class="text-right">
                                    <button type="button" class="btn btn-danger text-right" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success text-right">Add User</button>
                                </div>
                            </form>

                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

        </div>
    </div>
</div>

<!-- footer -->
<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
