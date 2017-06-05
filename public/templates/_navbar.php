<?php

$categories = $article->getCategories();

 ?>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<button aria-controls="navbar" aria-expanded="false" class="navbar-toggle collapsed" data-target="#navbar" data-toggle="collapse" type="button">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">PRACTICE BLOG</a>
		</div>
		<div class="collapse navbar-collapse" id="navbar">
			<ul class="nav navbar-nav">
				<li class="active"><a href="/">Home</a></li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Categories <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?php foreach ($categories as $category): ?>
							<li><a href="<?php echo '/category.php?c=' . $category['category_name']; ?>"><?php echo $category['category_name']; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</li>
				
				<li><a href="#contact">Contact</a></li>
				<li><a href="/admin">Admin panel</a></li>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</nav><!-- End of navigation -->
