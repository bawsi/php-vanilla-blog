<?php
// bootstrap
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'article';

$articleData = $article->getArticleById($_GET['id']);

include(TEMPLATES_PATH . '/_header.php');
?>

<?php if ($articleData !== false): ?>
	<!-- Main content -->
	<div class="container container-article">
		<div class="row">
			<div class="article-col col-md-10 col-md-offset-1">
				<?php if ($user->isLoggedIn()): ?>
					<a href="<?php echo '/admin/delete.php?id=' . $articleData['id']; ?>" class="btn btn-danger btn-xs pull-right">Delete</a>
					<a href="<?php echo '/admin/edit.php?id=' . $articleData['id']; ?>" class="btn btn-primary btn-xs pull-right">Edit</a>
				<?php endif; ?>
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

		<!-- disqus comments -->
		<div id="disqus_thread" class="comments">
			<script>

				/**
				*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
				*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
				/*
				var disqus_config = function () {
				this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
				this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
				};
				*/
				(function() { // DON'T EDIT BELOW THIS LINE
					var d = document, s = d.createElement('script');
					s.src = 'https://mysite-gbjknd3abq.disqus.com/embed.js';
					s.setAttribute('data-timestamp', +new Date());
					(d.head || d.body).appendChild(s);
				})();
			</script>
			<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
		</div>


	</div> <!-- End of main content -->

<?php else: ?>
	<h1 class="text-center">Article not found!</h1>
<?php endif; ?>

<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
