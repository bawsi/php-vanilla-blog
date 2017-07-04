<?php
//bootstrap
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'index';

// Getting articles
$perPage = 9;
$data = $article->getArticlesPaginated($perPage);

include(TEMPLATES_PATH . '/_header.php');
?>

<!-- Main content -->
<div class="container container-homepage">
	<h1 class="title text-center">Latest Articles</h1>
	<hr width="75%">
	<!-- Articles row -->
	<div class="row">
		<?php foreach ($data['articles'] as $article): ?>
			<!-- Single article -->
			<div class="single-article col-md-4 col-sm-6 col-xs-12">
				<div class="thumbnail">
					<a href="/article.php?id=<?php echo $article['id']; ?>"><img src="<?php echo $article['img_path']; ?>" alt="article image thumbnail"></a>
					<div class="caption">

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
					</div>

					<a href="article.php?id=<?php echo $article['id']; ?>" class="btn btn-block btn-primary read-more-btn" role="button">Read More</a>

				</div>
			</div> <!-- End of article -->
		<?php endforeach; ?>

	</div> <!-- End of articles row -->

	<!-- pagination -->
	<?php if ($data['numOfPages'] > 1):?>
		<nav aria-label="Page navigation" class="text-center">
			<ul class="pagination">

				<?php if($data['page'] > 1): ?>
					<li>
						<a href="/index.php?p=<?php echo $data['page'] - 1; ?>" aria-label="Previous">
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>
				<?php endif; ?>

				<?php for($pageCount = 1; $pageCount <= $data['numOfPages']; $pageCount++):?>
					<li class="<?php echo ($pageCount == $data['page']) ? 'active' : ''; ?>"><a href="/index.php?p=<?php echo $pageCount; ?>"><?php echo $pageCount; ?></a></li>
				<?php endfor; ?>

				<?php if($data['page'] < $data['numOfPages']): ?>
					<li>
						<a href="/index.php?p=<?php echo $data['page'] + 1; ?>" aria-label="Next">
							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				<?php endif; ?>

			</ul>
		</nav>
	<?php endif; ?>
</div> <!-- End of main content -->


<?php include(TEMPLATES_PATH . '/_footer.php') ?>
</body>
</html>