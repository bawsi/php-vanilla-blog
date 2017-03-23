<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

$db = new Db;
$article = new Article($db);

include(TEMPLATES_PATH . '/_header.php');
?>

<!-- Main content -->
<div class="container container-new-article">
	<div class="col-md-8 col-md-offset-2">

		<form class="article-form" action="save-article.php" method="post">
			<p>Article Title</p>
			<input type="text" name="title">
			<p>Article Body</p>
			<textarea name="body" rows="8"></textarea>
			<input type="hidden" name="authorId" value="2">
			<button type="submit" name="submit">Send now</button>
		</form>

	</div>
</div>
<!-- End of main content -->

<!-- CKEditor script that replaced textarea with ckeditor -->
<script>
    CKEDITOR.replace( 'body' );
</script>

<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
