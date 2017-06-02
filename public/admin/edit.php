<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

// If post request, update article
if ($_SERVER['REQUEST_METHOD'] == 'post') {
    $articleId = $_POST['id'];
    $title = $_POST['title'];
    $body = $_POST['body'];
    $categoryId = $_POST['category_id'];

} else {
    // Otherwise, get article by its id, so we can fill the form with its info
    $articleId = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $categories = $article->getCategories();


    $articleData = $article->getArticleById($articleId);


}

include(TEMPLATES_PATH . '/_header.php');
?>



<!-- Main content -->
<div class="container container-new-article">
	<div class="row">

		<!-- Sidebar column-->
		<?php include(TEMPLATES_PATH . '/admin/_side-nav.php'); ?>

		<!-- new article form column -->
		<div class="col-md-9">
			<form class="article-form" action="" method="post">
				<p>Article Title</p>
				<input type="text" name="title" placeholder="Article title here" class="form-control" value="<?php echo $articleData['title']; ?>">
				<p>Article Body</p>
				<textarea name="body" rows="8"><?php echo $articleData['body']; ?></textarea>
				<p>Category</p>
				<select class="category form-control" name="categoryId">
					<?php foreach ($categories as $category): ?>
						<option value='<?php echo $category["id"]; ?>' <?php echo ($category['category_name'] == $articleData['category_name'] ? 'selected' : ''); ?>><?php echo $category['category_name']; ?></option>
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




?>
