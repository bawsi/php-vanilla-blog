<?php
// database info
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'database_name');
define('DB_USER', 'database_username');
define('DB_PASS', 'database_password');

// paths
define("PUBLIC_PATH", dirname(__DIR__) . '/public');
define("APP_PATH", dirname(__DIR__) . '/app');
define("TEMPLATES_PATH", dirname(__DIR__) . '/public/templates');

// Other (Change JWT_KEY value)
define('JWT_KEY', 'qwpdjqwd0912uepj12je-912f12fpojqsfl');
define('SITE_URL', '');

// errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>
