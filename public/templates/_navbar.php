
<?php
$categories = $article->getCategories();
 ?>

<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top navbar-main">
	<div class="container">
		<div class="navbar-header">
			<button aria-controls="navbar" aria-expanded="false" class="navbar-toggle collapsed" data-target="#navbar" data-toggle="collapse" type="button">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">PRACTICE BLOG</a>
		</div>
		<div class="collapse navbar-collapse" id="navbar">
            <!-- Navbar left -->
            <ul class="nav navbar-nav">
				<li <?php echo ($currentPage == 'index') ? 'class="active"' : ''; ?>><a href="/">Home</a></li>

                <!-- Categories dropdown menu -->
                <li class="dropdown <?php echo ($currentPage == 'category') ? 'active' : ''; ?>">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Categories <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?php foreach ($categories as $category): ?>
							<li class="category-item"><a href="<?php echo '/category.php?c=' . $category['category_name']; ?>"><?php echo $category['category_name']; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</li>

				<!-- <li><a href="#contact">Contact</a></li> -->

                <!-- search -->
                <form class="navbar-form navbar-left search" action="/search.php" method="get">
                    <div class="form-group">
                        <input type="text" name="s" class="form-control input-sm search-input" placeholder="Search" required="required">
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm search-btn">Search</button>
                </form>

			</ul>

            <!-- Navbar on right -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Account dropdown menu -->
                <li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Account <span class="caret"></span></a>
					<ul class="dropdown-menu">

                        <?php if ($user->isLoggedIn()): // If logged in, show admin panel, settings and logout buttons, otherwise, show login button ?>
                            <li><a href="/admin">Admin panel</a></li>
                            <li><a href="/admin/settings.php">Account Settings</a></li>
                            <li><a href="/admin/logout.php">Logout</a></li>
                        <?php else: // Not logged, show login btn ?>
                            <li><a href="/admin/login.php">Login</a>'
                        <?php endif; ?>

					</ul>
				</li>
            </ul>

		</div><!--/.nav-collapse -->
	</div>
</nav><!-- End of navigation -->
