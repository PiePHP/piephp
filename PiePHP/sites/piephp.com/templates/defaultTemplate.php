<?php
if (!isset($title)) {
	$NEED_TITLE = true;
	$title = 'NEED_TITLE';
}
if (!is_ajax()) {
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
<head>
	<title><?php echo htmlentities($title); ?></title>
    <meta <?php echo 'property="og:title"'; ?> content="<?php echo htmlentities($title); ?>"/>
    <meta <?php echo 'property="og:type"'; ?> content="non_profit"/>
    <meta <?php echo 'property="og:url"'; ?> content="<?php echo $HTTP_ROOT; ?>"/>
    <meta <?php echo 'property="og:image"'; ?> content="<?php echo $URL_ROOT; ?>img/pie.gif"/>
    <meta <?php echo 'property="og:site_name"'; ?> content="PiePHP"/>
    <meta <?php echo 'property="fb:admins"'; ?> content="USER_ID"/>
    <meta <?php echo 'property="og:description"'; ?> content=""/>
	<link rel="stylesheet" href="<?php echo $URL_ROOT; ?>css/core-<?php echo $VERSION; ?>.css" type="text/css">
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
	<form id="form"></form>
	<div id="head">
		<a href="<?php echo $HTTP_ROOT; ?>" id="logo">It's PiePHP</a>
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
		<form action="<?php echo $HTTP_ROOT; ?>search" id="searchForm">
			<input type="hidden" name="cx" value="partner-pub-9402400169653768:3w9dl3-dv8z">
				<input type="hidden" name="cof" value="FORID:10">
				<input type="hidden" name="ie" value="ISO-8859-1">
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
		if (isset($contentTemplatePath) && !is_dialog()) {
			include $contentTemplatePath;
		}
		else {
			include $viewPath;
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
	if ($ENVIRONMENT == 'development' && isset($REFRESHER_ENABLED) && $REFRESHER_ENABLED) {
		?>
		<iframe id="refresher" src="<?php echo $URL_ROOT; ?>refresher" style="display:none"></iframe>
		<?php
	}
	if ($URL_ROOT != '/') {
		?>
		<script type="text/javascript">
		urlRoot = '<?php echo $URL_ROOT; ?>';
		</script>
		<?php
	}
	?>
	<script type="text/javascript" src="<?php echo $URL_ROOT; ?>js/core-<?php echo $VERSION; ?>.js"></script>
</body>
</html>
<?php
}
?>
