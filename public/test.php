<?php
//TODO delete this test file once done testing authentication
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));

// test comment
$db = new Db();
$database = $db->getConnection();
$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$user = new User($db);
if (true) {
    echo "Hi there, its true";
}
