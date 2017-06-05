<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

$articleData = $article->getArticleById($_GET['id']);

include(TEMPLATES_PATH . '/_header.php');
?>

<?php if ($articleData !== false): ?>
	<!-- Main content -->
	<div class="container container-article">
		<div class="row">
			<div class="article-col col-md-10 col-md-offset-1">
				<a href="<?php echo '/admin/delete.php?id=' . $articleData['id']; ?>" class="btn btn-danger btn-xs pull-right">Delete</a>
				<a href="<?php echo '/admin/edit.php?id=' . $articleData['id']; ?>" class="btn btn-primary btn-xs pull-right">Edit</a>
				<h2 class="article-title"><?php echo $articleData['title']; ?></h2>
				<h5 class="article-info">
					<span><i class="fa fa-calendar"></i> <?php echo htmlspecialchars(date('d.m.Y \a\t H:i', $articleData['created_at'])); ?></span>
					<span><i class="fa fa-user"></i> <?php echo htmlspecialchars($articleData['author']); ?></span>
					<span><i class="fa fa-folder-open-o"></i> <?php echo htmlspecialchars($articleData['category_name']); ?></span>
				</h5>
				<hr>
				<p class="article-body"><?php echo $articleData['body']; ?></p>
			</div>
		</div>
	</div> <!-- End of main content -->

<?php else: ?>
	<h1 class="text-center">Article not found!</h1>
<?php endif; ?>

<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
