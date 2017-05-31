<?php
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

$articleTitle = $_POST['title'];
$articleBody = $_POST['body'];
$articleAuthorId = (int)$_POST['authorId'];

if ($article->formDataIsValid($articleTitle, $articleBody, $articleAuthorId)) {
	if ($article->saveArticle($articleTitle, $articleBody, $articleAuthorId)) {
		echo "Article added.";
	}
	else {
		echo "Failed to save article to DB.";
	}
}
else {
	echo "Article verification failed.";
}

?>
