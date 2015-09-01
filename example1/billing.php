<?php
include "include.php";

/** Get the customer's ref */
$user_id = $_SESSION["user_id"];

if (FastSpring_Helper::is_subscribed($user_id)) {
	$redirectToUrl = "subpage1.php";
	header("Location: $redirectToUrl");
} else {
	$redirectToUrl = "select.php";
	header("Location: $redirectToUrl");
}

