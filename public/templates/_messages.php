<?php

// // Open div.container and div.col, so messages are centered
// echo '<div style="margin-top:20px;" class="container container-messages"><div class="col-md-6 col-md-offset-3">';
//
// /**
//  * If there is at least 1 error set in error_messages, show it, then unset it
//  */
// if (isset($_SESSION['error_messages']) && !empty($_SESSION['error_messages'])) {
// 	$messages = $_SESSION['error_messages'];
//
// 	// Displaying message
// 	echo '<div class="alert alert-danger alert-dismissible" role="alert">';
// 	echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></button>';
// 	echo '<strong>Error!</strong><ul>';
// 	// Display all seperate errors, in <li>
// 	foreach ($messages as $message): echo '<li>' . $message . '</li>'; endforeach;
// 	echo '</ul></div>';
//
// 	// Unset error_messages errors, once they are shown
// 	unset($_SESSION['error_messages']);
// }
//
// /**
//  * If there is at least 1 message in success_messages, show it, then unset it
//  */
// if (isset($_SESSION['success_messages']) && !empty($_SESSION['success_messages'])) {
// 	$messages = $_SESSION['success_messages'];
//
// 	// Displaying message
// 	echo '<div class="alert alert-success alert-dismissible" role="alert">';
// 	echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></button>';
// 	echo '<strong>Success!</strong><ul>';
// 	// Display all seperate errors, in <li>
// 	foreach ($messages as $message): echo '<li>' . $message . '</li>'; endforeach;
// 	echo '</ul></div>';
//
// 	// Unset success_messages errors, once they are shown
// 	unset($_SESSION['success_messages']);
// }
//
// // Close div.container and div.col
// echo '</div></div>';
?>
