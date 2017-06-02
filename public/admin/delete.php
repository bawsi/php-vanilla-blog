<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

$article = $article->delete($_GET['id']);
header('location: /admin/article-index.php');





?>
