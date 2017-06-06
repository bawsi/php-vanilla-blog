<?php
// bootstrap
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

// If not logged in, redirect to login page
if (!$user->isLoggedIn()) {
    header('location: /admin/login.php');
    die();
}

$article = $article->delete($_GET['id']);
header('location: /admin/article-index.php');





?>
