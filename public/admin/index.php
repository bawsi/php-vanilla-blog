<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

$articles = $article->getArticles(999);

include(TEMPLATES_PATH . '/_header.php')
?>

<div class="container container-admin-index">
    <table class="table table-condensed">
        <tr>
            <th>id</th>
            <th>Title</th>
            <th>Category</th>
            <th>Author</th>
            <th>Created on</th>
            <th></th>
        </tr>

        <?php foreach ($articles as $article):?>
            <tr>
                <td><?php echo htmlspecialchars($article['id']); ?></td>
                <td><?php echo htmlspecialchars($article['title']); ?></td>
                <td><?php echo htmlspecialchars($article['category_name']); ?></td>
                <td><?php echo htmlspecialchars($article['author']); ?></td>
                <td><?php echo htmlspecialchars(date("d.m.Y", $article['created_at'])); ?></td>
                <td>
                    <a href="#" class="btn btn-primary btn-xs">Edit</a>
                    <a href="#" class="btn btn-danger btn-xs">Delete</a>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>


</div>


<?php include(TEMPLATES_PATH . '/_footer.php'); ?>
