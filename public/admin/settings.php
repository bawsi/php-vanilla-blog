<?php
// bootstrap
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'settings';

// Redirect if not logged in
$user->redirectIfNotLoggedIn();

// Update user password
$user->updatePassword();

include(TEMPLATES_PATH . '/_header.php');
?>

<!-- Main content -->
<div class="container container-settings">va
	<div class="row">
		<!-- Settings form -->
		<div class="col-md-4 col-md-offset-4">
            <h2>Update your password</h2>
            <hr>
            <div id="settings-errors"></div>
			<form class="settings-form" action="" method="post">
                <input class="form-control" type="password" name="old-password" placeholder="Enter your old password" style="margin-bottom:5px;">
                <input class="form-control" type="password" name="new-password" placeholder="New password" style="margin-bottom:5px;">
                <input class="form-control" type="password" name="new-password-verify" placeholder="Verify new password" style="margin-bottom:10px;">
                <button type="submit" name="submit" class="btn btn-danger btn-block">Submit</button>
			</form>
		</div>

	</div>
</div>
<!-- End of main content -->

<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
<!--Validate form-->
<script src="../js/validate-settings.js"></script>
</body>
</html>