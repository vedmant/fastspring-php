<?php
include "include.php";

$privatekey = "my_private_key";
if (md5($_REQUEST["security_data"] . $privatekey) != $_REQUEST["security_hash"]) {
	return; /* FAILED CHECK */
}

$customer_ref = $_POST["customer_ref"];

if ($customer_ref == null) {
	header("HTTP/1.0 404 Not Found");
} else {
	unlink("$customer_data_dir/$customer_ref.txt");
}
?>