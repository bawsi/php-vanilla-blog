<?php
// bootstrap
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

// If not logged in, redirect to login page
if (!$user->isLoggedIn()) {
    header('location: /admin/login.php');
    die();
}

$page = 'admin-article-index';

// If POST request, update article
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $articleId = $_POST['articleId'];
    $title = $_POST['title'];
    $body = $_POST['body'];
    $image = $_FILES['image'];
    $categoryId = $_POST['categoryId'];

    if ($article->edit($articleId, $title, $body, $image, $categoryId)) {
        $_SESSION['success_messages'][] = 'Article successfully updated!';
        header('location: /article.php?id=' . $articleId);
    } else {
        $_SESSION['error_messages'][] = 'Failed to update the article. Try again please!';
        header('location: /admin/article-index.php');
    }

} else {
    // Otherwise, get article by its id, so we can fill the form below with its info
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

		<!-- edit article form column -->
		<div class="col-md-9">
			<form class="article-form" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="articleId" value="<?php echo $articleId; ?>">
				<p>Article Title</p>
				<input type="text" name="title" placeholder="Article title here" class="form-control" value="<?php echo $articleData['title']; ?>">
				<p>Article Body</p>
				<textarea name="body" rows="8"><?php echo $articleData['body']; ?></textarea>
                <p>Image (appears on article thumbnail / 400x200)</p>
				<input type="file" name="image" class="form-control" accept="image/*">
				<p>Category</p>
				<select class="category form-control" name="categoryId">
					<?php foreach ($categories as $category): ?>
						<option value='<?php echo $category["id"]; ?>' <?php echo ($category['category_name'] == $articleData['category_name'] ? 'selected' : ''); ?>><?php echo $category['category_name']; ?></option>
					<?php endforeach; ?>
				</select>
				<input type="hidden" name="authorId" value="1">
				<button type="submit" name="submit" class="btn btn-danger btn-block">Update article <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
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
