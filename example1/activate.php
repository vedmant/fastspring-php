<?php
include "include.php";

$privatekey = "my_private_key";
if (md5($_REQUEST["security_data"] . $privatekey) != $_REQUEST["security_hash"]) {
	return; /* FAILED CHECK */
}

$customer_ref = $_POST["customer_ref"];
$subscription_ref = $_POST["subscription_ref"];

if ($customer_ref == null) {
	header("HTTP/1.0 404 Not Found");
} else {
	$customer_data = fopen("$customer_data_dir/$customer_ref.txt", "w") or die("Can't open file.");
	fwrite($customer_data, $subscription_ref);
	fclose($customer_data);
}
?>
