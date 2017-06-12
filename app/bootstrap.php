<?php
use Psr\Http\Message\MessageInterface;
// Including config
include 'config.php';

// composer autoload
require APP_PATH . '/../vendor/autoload.php';

// Including models
include APP_PATH . '/models/Db.php';
include APP_PATH . '/models/Article.php';
include APP_PATH . '/models/User.php';

// Including Controllers
include APP_PATH . '/controllers/ArticleController.php';
include APP_PATH . '/controllers/UserController.php';

// Flash Messages (https://mikeeverhart.net/php-flash-messages/index.php)
$msg = new \Plasticbrain\FlashMessages\FlashMessages();

// Instantiating Models
$dbModel = new Db;
$articleModel = new Article($dbModel);
$userModel = new User($dbModel);

// Instantiating Controllers
$user = new UserController($userModel, $msg);
$article = new ArticleController($articleModel, $msg, $user);

?>
