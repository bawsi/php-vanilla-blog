<?php
// bootstrap and page variables
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'admin';
$page = 'admin-users';

// If not logged in, redirect to login page
$user->redirectIfNotLoggedIn();

// If not admin, redirect back to panel
$user->redirectIfNotAdmin();

// Grabbing list of all users
$users = $user->getUsersWithTotalAndLatestArticleTime();

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
                        <td><?php echo $user['total_articles']; ?></td>
                        <td><?php echo ($user['latest_article_time']) ? date('d.m.Y', $user['latest_article_time']) : 'User has not published any articles yet.'; ?></td>
                        <td>
                            <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#editUser<?php echo $user['id']; ?>">
                                 Edit
                            </button>
                            <a href="<?php echo '/admin/delete-user.php?id=' . $user['id']; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </table>

            <button class="btn btn-success" data-toggle="modal" data-target="#myModal">
                <i class="fa fa-plus-circle" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i>
                 Add user
            </button>

            <!-- New user modal  -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add new user</h4>
                        </div>
                        <div class="modal-body">

                            <!-- new user form -->
                            <div class="user-errors"></div>
                            <form class="user-form" id="new-user-form" action="/admin/new-user.php" method="post">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input class="form-control" type="text" name="username" placeholder="Username" style="margin-bottom:10px;">
                                    <label for="password">Password</label>
                                    <input class="form-control" type="password" name="password" placeholder="password" style="margin-bottom:10px;">
                                    <label for="role">User role</label>
                                    <select class="form-control" name="role">
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

            <!-- Edit user modal  -->
            <?php foreach ($users as $user): ?>
                    <div class="modal fade user-form" id="editUser<?php echo $user['id']; ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Edit user</h4>
                                </div>
                                <div class="modal-body">

                                    <!-- edit user form -->
                                    <div class="user-errors"></div>
                                    <form class="user-form" action="/admin/edit-user.php" method="post">
                                        <div class="form-group">
                                            <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                                            <label for="username">Username</label>
                                            <input class="form-control" type="text" name="username" placeholder="Username" value="<?php echo $user['username']; ?>" style="margin-bottom:10px;">
                                            <label for="password">New password</label>
                                            <input class="form-control" type="password" name="password" placeholder="New password" style="margin-bottom:10px;">
                                            <label for="role">User role</label>
                                            <select class="form-control" name="role">
                                                <option <?php echo ($user['role'] == 'writer') ? 'selected' : ''; ?> value="writer">Writer</option>
                                                <option <?php echo ($user['role'] == 'mod') ? 'selected' : ''; ?> value="mod">Moderator</option>
                                            </select>
                                        </div>
                                        <hr>
                                        <div class="text-right">
                                            <button type="button" class="btn btn-danger text-right" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success text-right">Update User</button>
                                        </div>
                                    </form>

                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
            <?php endforeach; ?>

        </div>
    </div>
</div>

<!-- footer -->
<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
<!--Login validation-->
<script src="../js/validate-new-user.js"></script>

</body>
</html>