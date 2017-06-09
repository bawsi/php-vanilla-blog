<?php
// bootstrap and page variables
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'admin';

// If not logged in, redirect to login page
$user->redirectIfNotLoggedIn();

$article = $article->delete($_GET['id']);
header('location: /admin/article-index.php');





?>
