<?php
// database info
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'database_name');
define('DB_USER', 'database_username');
define('DB_PASS', 'database_password');

// SMTP info
define('SMTP_HOST', 'smtp.sendgrid.net');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'username');
define('SMTP_PASSWORD', 'password');
define('SMTP_CONTACT_TO', 'youremail@gmail.com'); // Email, to which all contact me emails will go
define('SMTP_CONTACT_TO_NAME', 'John Doe');      // Name, that will appear next to your email

// paths
define("PUBLIC_PATH", dirname(__DIR__) . '/public');
define("APP_PATH", dirname(__DIR__) . '/app');
define("TEMPLATES_PATH", dirname(__DIR__) . '/public/templates');

// Other (Change JWT_KEY value)
define('JWT_KEY', 'set_custom_jwt_key_here');
define('SITE_URL', '');

// errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>
