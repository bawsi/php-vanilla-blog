<?php
// database info
define('DB_HOST', 'eu-cdbr-west-01.cleardb.com');
define('DB_NAME', 'heroku_a99d21812695f83');
define('DB_USER', 'bebada6f641e25');
define('DB_PASS', '557242ba');

// paths
define("PUBLIC_PATH", dirname(__DIR__) . '/public');
define("APP_PATH", dirname(__DIR__) . '/app');
define("TEMPLATES_PATH", dirname(__DIR__) . '/public/templates');

// Other
define('JWT_KEY', 'qwpdjqwd0912uepj12je-912f12fpojqsfl');
define('SITE_URL', 'site.dev');

// errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>
