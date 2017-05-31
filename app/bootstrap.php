<?php
// config
include 'config.php';

// Models
include APP_PATH . '/models/Db.php';
include APP_PATH . '/models/Article.php';
include APP_PATH . '/models/User.php';


$db = new Db;
$article = new Article($db);
$user = new User($db);

?>
