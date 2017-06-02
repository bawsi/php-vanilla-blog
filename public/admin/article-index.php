<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$page = 'admin-article-index';

$articles = $article->getArticles(999);

include(TEMPLATES_PATH . '/_header.php')
?>

<div class="container container-admin-index">
    <div class="row">
        <!-- side navigation -->
        <?php include(TEMPLATES_PATH . '/admin/_side-nav.php'); ?>

        <!-- articles table column -->
        <div class="col-md-9">
            <table class="table table-bordered" style="background: white;">
                <tr>
                    <th>id</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Created on</th>
                    <th>Options</th>
                </tr>
                <!-- Articles -->
                <?php foreach ($articles as $article):?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['id']); ?></td>
                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                        <td><?php echo htmlspecialchars($article['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($article['author']); ?></td>
                        <td><?php echo htmlspecialchars(date("d.m.Y", $article['created_at'])); ?></td>
                        <td>
                            <a href="<?php echo '/admin/edit.php?id=' . $article['id']; ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a href="<?php echo '/admin/delete.php?id=' . $article['id']; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times" aria-hidden="true"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>


<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
