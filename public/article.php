<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

$db = new Db;

$article = new Article($db);
$article = $article->getSingleArticleById($_GET['id']);

include(TEMPLATES_PATH . '/_header.php');
?>

<!-- Main content -->
<div class="container container-article">
	<div class="row">
		<div class="article-col col-md-10 col-md-offset-1">
			<h2 class="article-title"><?php echo $article['title']; ?></h2>
			<h5 class="article-info">
				<span><i class="fa fa-calendar"></i> <?php echo htmlspecialchars(date('d.m.Y \a\t H:i', $article['created_at'])); ?></span>
				<span><i class="fa fa-user"></i> <?php echo htmlspecialchars($article['author']); ?></span>
				<span><i class="fa fa-folder-open-o"></i> <?php echo htmlspecialchars($article['category_name']); ?></span>
			</h5>
			<hr>
			<p class="article-body"><?php echo $article['body']; ?></p>
		</div>
	</div>
</div> <!-- End of main content -->

<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
