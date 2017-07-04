<?php
// bootstrap and page variables
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'admin';
$page = 'admin-new-article';

// If not logged in, redirect to login page
$user->redirectIfNotLoggedIn();

// Validate and store article on POST, or get list of categories on GET
$categories = $article->validateAndStoreArticle();

include(TEMPLATES_PATH . '/_header.php');
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
			<form class="article-form" action="" method="post" enctype="multipart/form-data">
				<p>Article Title</p>
				<input type="text" name="title" placeholder="Article title here" class="form-control" required="required">
				<p>Article Body</p>
				<textarea name="body" rows="8"></textarea>
				<p>Image (appears on article thumbnail / 400x200)</p>
				<input type="file" name="image" class="form-control" accept="image/*">
				<p>Category</p>
				<select class="category form-control" name="categoryId">
					<?php foreach ($categories as $category): ?>
						<option value='<?php echo $category["id"]; ?>'><?php echo $category['category_name']; ?></option>
					<?php endforeach; ?>
				</select>
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
</body>
</html>