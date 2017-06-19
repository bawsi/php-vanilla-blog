<?php
// bootstrap and page variables
include(realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/bootstrap.php'));
$currentPage = 'admin';

// If not logged in, redirect to login page
$user->redirectIfNotLoggedIn();

// Attempt to register new user
$user->newUser();
