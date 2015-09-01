<?php
include "include.php";

if (! FastSpring_Helper::is_subscribed($_SESSION["user_id"])) {
	header("Location: billing.php");

	exit();
}

$subscription_ref = FastSpring_Helper::get_subscription_ref($_SESSION["user_id"]);

if (isset($_POST["cancel"])) {
	try {
		$cancelSub = $fastspring->cancelSubscription($subscription_ref);
	} catch (FsprgException $cancelEx) {
		// die($cancelEx);
	}
} elseif (isset($_POST["renew"])) {
	try {
		$fastspring->renewSubscription($subscription_ref);
	} catch (FsprgException $renewEx) {
		// Error can be handled here
	}
} elseif (isset($_POST["update"])) {
	$update = new FsprgSubscriptionUpdate($subscription_ref);

	if(isset($_POST["productPath"])) $update->productPath = $_POST["productPath"];
	if(isset($_POST["tags"])) $update->tags = $_POST["tags"];
	if(isset($_POST["quantity"])) $update->quantity = $_POST["quantity"];
	if(isset($_POST["coupon"])) $update->coupon = $_POST["coupon"];
	if(isset($_POST["discountduration"])) $update->discountDuration = $_POST["discountduration"];
	if(isset($_POST["noenddate"])) $update->noEndDate = true;
	if(isset($_POST["proration"])) $update->proration = true;
	else $update->proration = false;

	try {
		$updateSub = $fastspring->updateSubscription($update);
	} catch (FsprgException $updateEx) {
		// Error can be handled here
	}
}

try {
	$getSub = $fastspring->getSubscription($subscription_ref);
} catch (FsprgException $getEx) {
	// Error can be handled here
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Example1 - Subscription Page 1</title>

<!--
	Note: T
	-->

<!-- 
	These system styles are automatically included, but are useful here for testing on a local machine 
-->
<link title="main" rel="stylesheet" href="http://resource.fastspring.com/app/s/style/base.css" media="screen,projection" type="text/css" />
<link title="main" rel="stylesheet" href="http://resource.fastspring.com/app/store/style/base.css" media="screen,projection" type="text/css" />

<!-- 
	Custom style
-->
<link href="style.css" rel="stylesheet" type="text/css" />

</head>

<!-- 
	Note: No attributes should appear on the body tag.  They will be ignored.  Use CSS to style the body.
-->
<style>
	.formRow{clear: both; padding-top: 5px;}
	.formRow .formLabel{float: left; width: 150px; text-align: right;}
	.formRow .formInput{text-align: left;}
	.change-plan.selected input[type="submit"]{color: red;};
</style>
<body> 
<div id="header">
	<ul id="menu">
		<li class="left"><a href="#" accesskey="1" title="">Home</a></li>
		<li class="left"><a href="#" accesskey="2" title="">Products</a></li>
		<li class="left"><a href="#" accesskey="3" title="">Information</a></li>
		<li class="left"><a href="#" accesskey="4" title="">About</a></li>
		<li class="left"><a href="#" accesskey="5" title="">Contact</a></li>
	</ul>
</div>
<div id="content">
	<div id="colOne">
		<div id="logo">
			<h1><a href="#">Store</a></h1>
		</div>
		<div class="box">
			<h3>Subscription Pages</h3>
			<ul class="bottom">
				<li class="first"><a href="subpage1.php">Subscription Page</a></li>
			</ul>
		</div>
		<div class="box">
			<h3>Mauris cras libero</h3>
			<ul class="bottom">
				<li class="first"><a href="#">Sed accumsan congue</a></li>
				<li><a href="#">Nulla dignissim nec augue</a></li>
				<li><a href="#">Nunc ante elit nulla</a></li>
				<li><a href="#">Aliquam suscipit consequat</a></li>
				<li><a href="#">Cursus sed arcu sed</a></li>
				<li><a href="#">Nulla dignissim nec augue</a></li>
			</ul>
		</div>
	</div>
	<div id="colTwo" style="padding-bottom: 20px">
		
		<!-- TemplateBeginEditable name="Content" -->

		<h2>Subscription Page</h2>
	
		<p>
			This is an example of how to implement a subscription page using FastSpring API.
			Below you will see examples for the available APIs.  
		</p>

		<h3>Subscription Info</h3>
		
		<h4>Using the FastSpring::getSubscription API</h4>
		
		<?php if (! empty($getSub)):  ?>
			<div>
				<ul>
					<li>status: <?php echo $getSub->status ?></li>
					<li>statusChanged: <?php echo date('Y-m-d H:i:s', $getSub->statusChanged) ?></li>
					<li>statusReason: <?php echo $getSub->statusReason ?></li>
					<li>cancelable: <?php echo $getSub->cancelable ?></li>
					<li>reference: <?php echo $getSub->reference ?></li>
					<li>test: <?php echo $getSub->test ?></li>
					<li>customer firstName: <?php echo $getSub->customer->firstName ?></li>
					<li>customer lastName: <?php echo $getSub->customer->lastName ?></li>
					<li>customer company: <?php echo $getSub->customer->company ?></li>
					<li>customer email: <?php echo $getSub->customer->email ?></li>
					<li>customer phoneNumber: <?php echo $getSub->customer->phoneNumber ?></li>
					<li>customerUrl: <?php echo $getSub->customerUrl ?></li>
					<li>productName: <?php echo $getSub->productName ?></li>
					<li>tags: <?php echo $getSub->tags ?></li>
					<li>quantity: <?php echo $getSub->quantity ?></li>
					<li>nextPeriodDate: <?php if ($getSub->nextPeriodDate) echo date('Y-m-d', $getSub->nextPeriodDate) ?></li>
					<li>end: <?php if ($getSub->end) echo date('Y-m-d', $getSub->end) ?></li>
				</ul>
			</div>

		<?php else: ?>
			<div style="font-weight: bold">
				There was an error getting the subscription.

				<ul>
					<li>http status code: <?php echo $getEx->httpStatusCode ?></li>
					<li>error code: <?php echo $getEx->errorCode ?></li>
				</ul>
			</div>
		<?php endif; ?>

		
		<h3>Update Subscription</h3>
		
		<h4>Using the FastSpring::updateSubscription API</h4>

		<form method="post">
			<div class="formRow"><span class="formLabel">Product Path:</span><span class="formInput"><input type="text" name="productPath" value=""/></span>
				Partial URL Path. E.g. /plana. See <a href="https://support.fastspring.com/entries/20773966-page-linking-options">Page Linking Options</a>
			</div>
			<div class="formRow"><span class="formLabel">Tags:</span><span class="formInput"><input type="text" name="tags" value="<?php echo $getSub->tags?>"/></span></div>
			<div class="formRow"><span class="formLabel">Quantity:</span><span class="formInput"><input type="text" name="quantity" value="<?php echo $getSub->quantity?>"/></span></div>
			<div class="formRow"><span class="formLabel">Coupon:</span><span class="formInput"><input type="text" name="coupon" value=""/></span></div>
			<div class="formRow"><span class="formLabel">DiscountDuration:</span><span class="formInput"><input type="text" name="discountduration" value=""/></span></div>
			<div class="formRow"><span class="formLabel">No End Date:</span><span class="formInput"><input type="checkbox" name="noenddate" value="noenddate"/></span></div>
			<div class="formRow"><span class="formLabel">Proration:</span><span class="formInput"><input type="checkbox" name="proration" value="proration"/></span>
				If true a prorated refund will be made to reimburse the customer for their current use. See <a href="https://support.fastspring.com/entries/20077837-upgrading-downgrading-changing-plans">Upgrading / Downgrading / Changing Plans</a>
			</div>
			<div class="formRow"><input type="submit" name="update" value="Update Subscription"/></div>
		</form>

		<h4>Or update Plan</h4>

		<?php foreach($fs_plans as $plan_id => $plan_name): ?>

			<form method="post" class="change-plan <?php if($getSub->productName == $plan_name) echo 'selected'; ?>">
				<input type="hidden" name="productPath" value="<?php echo $plan_id; ?>"/>
				<input type="hidden" name="proration" value="1"/>
				<input type="submit" name="update" value="<?php echo $plan_name; ?>"/>
			</form>

		<?php endforeach; ?>

		<br><br>
		
		<?php if (isset($updateSub)): ?>
			<p style="font-weight: bold">
				The update request was successful.
			</p>
		<?php  elseif (isset($updateEx)): ?>
			<div style="font-weight: bold">
				The update request has the following results:
				<ul>
					<li>http status code: <?php echo $updateEx->httpStatusCode ?></li>
					<li>error code: <?php echo $updateEx->errorCode ?></li>
				</ul>
			</div>
		<?php endif; ?>


		<h3>Cancel Subscription</h3>
		
		<h4>Using the FastSpring::cancelSubscription API</h4>

		<form method="post">
			<div class="formRow"><input type="submit" name="cancel" value="Cancel Subscription"/></div>
		</form>

		<br>
		<?php if (isset($cancelSub)): ?>
			<p style="font-weight: bold">
				The cancel request was successful.
			</p>
		<?php elseif (isset($cancelEx)): ?>
			<div style="font-weight: bold">
				The cancel request has the following results:
				<ul>
					<li>http status code: <?php echo $cancelEx->httpStatusCode ?></li>
					<li>error code: <?php echo $cancelEx->errorCode ?></li>
				</ul>
			</div>
		<?php endif; ?>


		<h3>Renew Subscription</h3>
		
		<h4>Using the FastSpring::renewSubscription API</h4>

		<form method="post">
			<div class="formRow"><input type="submit" name="renew" value="Renew Subscription"/></div>
		</form>

		<br>
		<?php if (isset($_POST["renew"])): ?>
			<?php if (!isset($renewEx)): ?>
				<p style="font-weight: bold">
					The renew request was successful.
				</p>
			<?php else: ?>
				<div style="font-weight: bold">
					The renew request has the following results:
					<ul>
						<li>http status code: <?php echo $renewEx->httpStatusCode ?></li>
						<li>error code: <?php echo $renewEx->errorCode ?></li>
					</ul>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<!-- TemplateEndEditable -->		
		
	</div>
</div>
<div id="footer">
	<p>Copyright &#169; Sitename.com. All rights reserved. Design by <a href="http://freecsstemplates.org/">Free CSS Templates</a>.</p>
</div>
</body>
</html>
