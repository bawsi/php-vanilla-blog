<?php
// database info
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog');
define('DB_USER', 'bawsi');
define('DB_PASS', '123');

// paths
define("PUBLIC_PATH", dirname(__DIR__) . '/public');
define("APP_PATH", dirname(__DIR__) . '/app');
define("TEMPLATES_PATH", dirname(__DIR__) . '/public/templates');

// errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>
