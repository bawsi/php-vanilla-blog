<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

$articles = $article->getArticles(10);

include(TEMPLATES_PATH . '/_header.php')
?>

<!-- Main content -->
<div class="container container-homepage">
	<h1 class="title text-center">Latest Articles</h1>
	<hr width="75%">
	<!-- Articles row -->
	<div class="row">

		<?php foreach ($articles as $article): ?>
			<!-- Single article -->
			<div class="single-article col-md-4 col-sm-6 col-xs-12">
				<div class="thumbnail">
					<img src="http://placehold.it/350x200" alt="article image thumbnail">
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

							<span>
								<i class="fa fa-folder-open-o"></i>
								<?php echo htmlspecialchars($article['category_name']); ?>
							</span>
						</p>

						<h3><?php echo $article['title']; ?></h3>
						<p style="margin-top: 25px;"><a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-block btn-primary" role="button">Read More</a></p>

					</div>
				</div>
			</div> <!-- End of article -->
		<?php endforeach; ?>

	</div> <!-- End of articles row -->
</div> <!-- End of main content -->

<?php include(TEMPLATES_PATH . '/_footer.php') ?>
