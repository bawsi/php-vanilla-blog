<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

// If post request, update article
if (SERVER['REQUEST_METHOD'] == 'post') {
    $articleId = $_POST['id'];
    $title = $_POST['title'];
    $body = $_POST['body'];
    $categoryId = $_POST['category_id'];
    
    header('location: /admin/article?id=' . $id);
}


// if not post request, show form





?>
