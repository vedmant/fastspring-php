<?php
include "include.php";

/** Get the customer's ref */
$customer_ref = $_SESSION["customer_ref"];

if (isSubscribed($customer_ref)) {
	$redirectToUrl = "subpage1.php";
	 
	header("Location: $redirectToUrl");
} else {
	$fastspring->createSubscription(product_id, $customer_ref);
}

exit();
?>