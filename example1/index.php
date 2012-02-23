<?php
include 'include.php'; 
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Example1 - Homepage</title>

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
				<li class="first"><a href="/billing.php">Subscription Page</a></li>
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
	<div id="colTwo" style="padding-bottom: 25px">
		
		<!-- TemplateBeginEditable name="Content" -->

		<h2>Homepage</h2>
	
		<p style="text-align:left">
			This is an example web application that will demonstrate how into integrate into
			FastSpring's Subscription API.
		</p>
		
		<p style="text-align:left">
			You are currently simulated to be logged in with customer_ref = <?php echo $_SESSION['customer_ref'] ?>.
			This number will be passed to FastSpring and after the payment is processed the number will be passed
			back to the activate.php script.
		</p>
		
		<p style="text-align:left">
			Click on the Subscription Page link on the left nav to see the FastSpring subscription service in action.
		</p>

		<h2>Configuration</h2>

		<h3>FastSpring Store</h3>
		
		<p style="text-align:left">
			Your FastSpring store needs to be configured in order to use this example. The following items need to be
			in configured:
		</p>
		
		<p>
			<ul>
				<li>A subscription product</li>
				<li>Subscription Activated Notification</li>
				<li>Subscription Deactivated Notification</li>
			</ul>
		</p>
		
		<h3>Web Application</h3>
		
		<p style="text-align:left">
			You need to modify include.php to modify the following values to match your store's settings:
		</p>
		
		<p>
			<ul>
				<li>$store_id</li>
				<li>$api_username</li>
				<li>$api_password</li>
				<li>$test_mode</li>
				<li>product_id</li>
			</ul>
		</p>
		
		<p style="text-align:left">
			You also need to set the $privateKey variable in activate.php and deactivate.php. This private key
			needs to match the private key in the Security tab of the Subscription Activated Notification
			and Subscription Deactivated Notification respectively.
		</p>
		
		<!-- TemplateEndEditable -->		
		
	</div>
</div>
<div id="footer">
	<p>Copyright &#169; Sitename.com. All rights reserved. Design by <a href="http://freecsstemplates.org/">Free CSS Templates</a>.</p>
</div>
</body>
</html>
