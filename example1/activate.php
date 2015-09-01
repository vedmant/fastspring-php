<?php
include "include.php";

file_put_contents('activate.txt', date('Y-m-d H:i:s')."\n\n".print_r($_POST, true)."\n\n", FILE_APPEND);

$privatekey = "9cf4b215ecda1d7d1e66f375e4514573";
if (md5($_REQUEST["security_data"] . $privatekey) != $_REQUEST["security_hash"]) {
	return; /* FAILED CHECK */
}

$user_id = Arr::get($_POST, "SubscriptionReferrer");
$subscription_ref = Arr::get($_POST, "SubscriptionReference");

if ($user_id == null) {
	header("HTTP/1.0 404 Not Found");
} else {
	try {
		$subscription_data = $fastspring->getSubscription($subscription_ref);
		FastSpring_Helper::save_subscription($user_id, $subscription_data);
	} catch (FsprgException $getEx) {
		file_put_contents('activate.txt', 'Error: '.$getEx->getMessage()."\n", FILE_APPEND);
	}
}

file_put_contents('activate.txt', "\n\n\n", FILE_APPEND);

