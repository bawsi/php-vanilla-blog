<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$page = 'admin-new-article';

// Getting list of all categories
$categories = $article->getCategories();

// header template (included before other code, because otherwise,
// success_messages session variable gets unset right after it is set
include(TEMPLATES_PATH . '/_header.php');

// If it is POST request, new article was already submitted. Validate & store it
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$articleTitle = $_POST['title'];
	$articleBody = $_POST['body'];
	$articleCategory = $_POST['category'];
	$articleAuthorId = (int)$_POST['authorId'];

	if ($articleId = $article->validateAndStoreArticle($articleTitle, $articleBody, $articleCategory, $articleAuthorId)) {
		header('location: /article.php?id=' . $articleId);
	} else {
		// Including messages again, since otherwise error message that was set
		// wont be displayed untill next request
		include(TEMPLATES_PATH . '/_messages.php');
	}
}


?>

<!-- Main content -->
<div class="container container-new-article">
	<div class="row">
        <div class="col-md-9 col-md-offset-3">
            <h1>Write a new article</h1>
            <br>
        </div>
    </div>

	<div class="row">

		<!-- Sidebar column-->
		<?php include(TEMPLATES_PATH . '/admin/_side-nav.php'); ?>

		<!-- new article form column -->
		<div class="col-md-9">
			<form class="article-form" action="" method="post">
				<p>Article Title</p>
				<input type="text" name="title" placeholder="Article title here" class="form-control">
				<p>Article Body</p>
				<textarea name="body" rows="8"></textarea>
				<p>Category</p>
				<select class="category form-control" name="category">
					<?php foreach ($categories as $category): ?>
						<option value='<?php echo $category["id"]; ?>'><?php echo $category['category_name']; ?></option>
					<?php endforeach; ?>
				</select>
				<input type="hidden" name="authorId" value="1">
				<button type="submit" name="submit" class="btn btn-danger btn-block">Publish article <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
			</form>
		</div>

	</div>
</div>
<!-- End of main content -->

<!-- CKEditor script that replaced textarea with ckeditor -->
<script>
    CKEDITOR.replace('body');
</script>

<!-- footer -->
<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
