<?php
// database info
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog');
define('DB_USER', 'bawsi');
define('DB_PASS', 'misko123');

// paths
define("PUBLIC_PATH", dirname(__DIR__) . '/public');
define("APP_PATH", dirname(__DIR__) . '/app');
define("TEMPLATES_PATH", dirname(__DIR__) . '/public/templates');

// Other
define('JWT_KEY', 'qwpdjqwd0912uepj12je-912f12fpojqsfl');
define('SITE_URL', '');

// errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>
