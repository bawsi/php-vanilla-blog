<?php
// Including config
include 'config.php';

// Including models
include APP_PATH . '/models/Db.php';
include APP_PATH . '/models/Article.php';
include APP_PATH . '/models/User.php';

// Including Controllers
include APP_PATH . '/controllers/ArticleController.php';
include APP_PATH . '/controllers/UserController.php';

// Instantiating Models and Controllers
$dbModel = new Db;
$articleModel = new Article($dbModel);

$article = new ArticleController($articleModel);
// $user = new UserController($userModel);

?>
