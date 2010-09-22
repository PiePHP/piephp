<?php
if (!is_ajax()) {
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
<head>
	<title>PiePHP - <?php echo $title; ?></title>
	<link rel="stylesheet" href="/css/base.css" type="text/css">
	<link rel="stylesheet" href="/css/scaffolds.css" type="text/css">
	<link rel="stylesheet" href="/css/veil.css" type="text/css">
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
	<form id="veil"></form>
	<div id="head">
		<a href="<?php echo $HTTP_ROOT; ?>" id="logo">PiePHP</a>
		<div id="user">
			<div id="userNav">
				<a href="<?php echo $HTTP_ROOT; ?>sign_up" class="veil"><b>Sign Up</b></a>
				<a href="<?php echo $HTTP_ROOT; ?>sign_in" class="veil"><b>Sign In</b></a>
			</div>
		</div>
	</div>
	<div id="nav">
		<span>
			<a href="<?php echo $HTTP_ROOT; ?>downloads">Downloads</a>
			<a href="<?php echo $HTTP_ROOT; ?>tutorials">Tutorials</a>
			<a href="<?php echo $HTTP_ROOT; ?>documentation">Documentation</a>
			<a href="<?php echo $HTTP_ROOT; ?>forums">Forums</a>
			<a href="<?php echo $HTTP_ROOT; ?>community">Community</a>
		</span>
		<form action="<?php echo $HTTP_ROOT; ?>search">
			<input type="text" id="search" name="q" title="Search PiePHP.com">
			<button id="go"></button>
		</form>
	</div>
	<div id="body">
		<?php
		}
		else {
			?><var id="title">PiePHP - <?php echo $title; ?></var><?php
		}
		//echo 'manualContentLayout: ' . (isset($manualContentLayout) ? 'true' : 'false') . '<br>';
		//echo 'is_dialog(): ' . (is_dialog() ? 'true' : 'false') . '<br>';
		if (isset($manualContentLayout) || is_dialog()) {
			include $viewPath;
		}
		else {
			?>
			<div id="content">
			<?php
			include $viewPath;
			?>
			<br class="clear">
			</div>
			<?php
		}
		?>
		<?php
		if (!is_ajax()) {
		?>
	</div>
	<div id="foot">
		Copyright 2007-2010, Pie Software Foundation, All rights reserved
	</div>
	<?php
	if ($GLOBALS['ENVIRONMENT'] == 'development') {
		?>
		<iframe id="refresher" src="<?php echo $URL_ROOT; ?>refresher" style="display:none"></iframe>
		<?php
	}
	?>
	<script type="text/javascript" src="/js/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="/js/base.js"></script>
	<script type="text/javascript" src="/js/uservoice.js"></script>
	<script type="text/javascript" src="/js/google_analytics.js"></script>
</body>
</html>
<?php
}
?>