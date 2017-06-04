<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

// pagination
$page = (isset($_GET['p'])) ? $_GET['p'] : 1;
$perPage = 9;
$totalPages = $article->getTotalNumberOfPages($perPage);

$articles = $article->getArticlesPaginated($page, $perPage);

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
					<img src="<?php echo $article['img_path']; ?>" alt="article image thumbnail">
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
						<a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-block btn-primary read-more-btn" role="button">Read More</a>

					</div>
				</div>
			</div> <!-- End of article -->
		<?php endforeach; ?>

	</div> <!-- End of articles row -->

	<!-- pagination -->
	<nav aria-label="Page navigation" class="text-center">
		<ul class="pagination">

			<?php if($page > 1): ?>
				<li>
					<a href="/index.php?p=<?php echo $page - 1; ?>" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
					</a>
				</li>
			<?php endif; ?>

			<?php for($pageCount = 1; $pageCount <= $totalPages; $pageCount++):?>
				<li><a href="/index.php?p=<?php echo $pageCount; ?>"><?php echo $pageCount; ?></a></li>
			<?php endfor; ?>

			<?php if($page < $totalPages): ?>
				<li>
					<a href="/index.php?p=<?php echo $page + 1; ?>" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
					</a>
				</li>
			<?php endif; ?>

		</ul>
	</nav>
</div> <!-- End of main content -->


<?php include(TEMPLATES_PATH . '/_footer.php') ?>
