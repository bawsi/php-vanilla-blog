<?php
// bootstrap
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'search';

$articles = $article->search();

include(TEMPLATES_PATH . '/_header.php')
?>

<div class="container container-homepage">
	<h1 class="title text-center">Search results for '<?php echo $_GET['s']; ?>'</h1>
	<hr width="75%">
	<!-- Articles row -->
	<div class="row">
		<?php foreach ($articles as $article): ?>
			<!-- Single article -->
			<div class="single-article col-md-4 col-sm-6 col-xs-12">
				<div class="thumbnail">
					<a href="/article.php?id=<?php echo $article['id']; ?>"><img src="<?php echo $article['img_path']; ?>" alt="article image thumbnail"></a>
					<div class="caption" style="padding-bottom: 0">

						<p class="article-info">
							<span>
								<i class="fa fa-calendar"></i>
								<?php echo htmlspecialchars(date('d.m.Y', $article['created_at'])); ?>
							</span>

							<span>
								<i class="fa fa-user"></i>
								<?php echo htmlspecialchars($article['author']); ?>
							</span>

							<span class="category">
								<i class="fa fa-folder-open-o"></i>
								<a href="/category.php?c=<?php echo htmlspecialchars($article['category_name']); ?>"><?php echo htmlspecialchars($article['category_name']); ?></a>
							</span>
						</p>

						<a class="article-title" href="/article.php?id=<?php echo $article['id']; ?>"><h3><?php echo $article['title']; ?></h3></a>
						<a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-block btn-primary read-more-btn" role="button">Read More</a>

					</div>
				</div>
			</div> <!-- End of article -->
		<?php endforeach; ?>

	</div> <!-- End of articles row -->

</div> <!-- End of main content -->


<?php include(TEMPLATES_PATH . '/_footer.php') ?>
