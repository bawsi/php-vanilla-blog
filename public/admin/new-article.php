<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));


// If POST request, new article was already submitted. Validate & store it
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$articleTitle = $_POST['title'];
	$articleBody = $_POST['body'];
	$articleAuthorId = (int)$_POST['authorId'];

	if ($articleId = $article->validateAndStoreArticle($articleTitle, $articleBody, $articleAuthorId)) {
		$success_messages[] = 'Article successfully added.';
		header('location: /article.php?id=' . $articleId);
	}
	else {
		$error_messages[] = 'Failed to submit article. Try again!';
	}
}



include(TEMPLATES_PATH . '/_header.php');
?>

<!-- Main content -->
<div class="container container-new-article">
	<div class="col-md-8 col-md-offset-2">

		<form class="article-form" action="" method="post">
			<p>Article Title</p>
			<input type="text" name="title">
			<p>Article Body</p>
			<textarea name="body" rows="8"></textarea>
			<input type="hidden" name="authorId" value="1">
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
