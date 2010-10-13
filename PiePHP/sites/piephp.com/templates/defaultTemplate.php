<?php
if (!isset($title)) {
	$NEED_TITLE = true;
	$title = 'NEED_TITLE';
}
if (!is_ajax()) {
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
<head>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" href="<?php echo $URL_ROOT; ?>core-<?php echo $VERSION; ?>.css" type="text/css">
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
	<form id="form"></form>
	<div id="head">
		<a href="<?php echo $HTTP_ROOT; ?>" id="logo">PiePHP</a>
		<div id="user">
			<u id="userNav">
				<a href="<?php echo $HTTP_ROOT; ?>sign_up" class="veil"><b>Sign Up</b></a>
				<a href="<?php echo $HTTP_ROOT; ?>sign_in" class="veil"><b>Sign In</b></a>
			</u>
		</div>
	</div>
	<div id="nav">
		<span>
			<a href="<?php echo $HTTP_ROOT; ?>downloads">Downloads</a>
			<a href="<?php echo $HTTP_ROOT; ?>tutorials">Tutorials</a>
			<a href="<?php echo $HTTP_ROOT; ?>user_guide">User guide</a>
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
			?><var id="title"><?php echo $title; ?></var><?php
		}
		?>
		<var>NOTIFICATIONS</var>
		<?php
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
			<iframe id="sky" class="gAd" title="9402400169653768/3278761672" frameborder="no" src="about:blank"></iframe>
			<?php
		}
		?>
		<?php
		if (!is_ajax()) {
		?>
	</div>
	<div id="foot">
		Copyright &copy; 2007-2010, Pie Software Foundation, All rights reserved
	</div>
	<?php
	if ($ENVIRONMENT == 'development') {
		?>
		<iframe id="refresher" src="<?php echo $URL_ROOT; ?>refresher" style="display:none"></iframe>
		<?php
	}
	?>
	<script type="text/javascript" src="<?php echo $URL_ROOT; ?>core-<?php echo $VERSION; ?>.js"></script>
</body>
</html>
<?php
}
?>
