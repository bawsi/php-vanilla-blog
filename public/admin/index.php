<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$page = 'article-index';

$articles = $article->getArticles(999);

include(TEMPLATES_PATH . '/_header.php')
?>

<div class="container container-admin-index">

    <?php include(TEMPLATES_PATH . '/admin/_breadcrumbs.php'); ?>

    <div class="row">

        <!-- side navigation -->
        <?php include(TEMPLATES_PATH . '/admin/_side-nav.php'); ?>

        <!-- article table -->
        <div class="col-md-9">
            <table class="table table-condensed table-bordered" style="background: white;">
                <tr>
                    <th>id</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Created on</th>
                    <th>Options</th>
                </tr>

                <?php foreach ($articles as $article):?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['id']); ?></td>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td><?php echo htmlspecialchars($article['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($article['author']); ?></td>
                        <td><?php echo htmlspecialchars(date("d.m.Y", $article['created_at'])); ?></td>
                        <td>
                            <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>
        </div>
    </div>


</div>


<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
