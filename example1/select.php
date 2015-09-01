<?php
include "include.php";

if (isset($_POST["plan"])) {
	$fastspring->createSubscription($_POST["plan"], $_SESSION["user_id"]);
	die();
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Example1 - Select Plan</title>

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
	.plans-list{ list-style-type: none; margin: 0 -5px;}
	.plans-list:after{content: ' '; display: table; clear: both;}
	.plans-list > li{float: left; width: 25%;}
	.plans-list .plan-item{margin: 5px; padding: 20px; border: 1px solid grey; text-align: center;}
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

		<h2>Select subscription plan</h2>
	
		<p>
			This is an example of how to implement a select subscription page using FastSpring API.
		</p>

		<h3>Available plans:</h3>

		<ul class="plans-list">
			<?php foreach($fs_plans as $plan_id => $plan_name): ?>
				<li>
					<div class="plan-item">
						<h4><?php echo $plan_name; ?></h4>
						<form method="post">
							<input type="hidden" name="plan" value="<?php echo $plan_id; ?>"/>
							<input type="submit" name="update" value="Subscribe"/>
						</form>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<!-- TemplateEndEditable -->
	</div>
</div>
<div id="footer">
	<p>Copyright &#169; Sitename.com. All rights reserved. Design by <a href="http://freecsstemplates.org/">Free CSS Templates</a>.</p>
</div>
</body>
</html>
