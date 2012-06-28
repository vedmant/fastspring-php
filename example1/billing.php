<?php
include "include.php";

/** Get the customer's ref */
$customer_ref = $_SESSION["customer_ref"];

$file = customer_data_dir."/$customer_ref.txt";
if (file_exists($file)) {
	$customer_data = fopen($file, "r");
}
if (!$customer_data) {
	$fastspring->createSubscription(product_id, $customer_ref);

	exit();
} else {
	if (isSubscribed($customer_ref, $productId)) {
		$redirectToUrl = "subpage1.php";
		 
		header("Location: $redirectToUrl");
		 
		exit();
	}
}
?>