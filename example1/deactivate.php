<?php
include "include.php";

file_put_contents('deactivate.txt', date('Y-m-d H:i:s')."\n\n".print_r($_POST, true)."\n\n\n", FILE_APPEND);

$privatekey = "90b06da8c09f70fb01fe22a02c1e4f71";
if (md5($_REQUEST["security_data"] . $privatekey) != $_REQUEST["security_hash"]) {
	return; /* FAILED CHECK */
}

$user_id = Arr::get($_POST, "SubscriptionReferrer");

if ($user_id == null) {
	header("HTTP/1.0 404 Not Found");
} else {
	FastSpring_Helper::delete_subscription($user_id);
}
