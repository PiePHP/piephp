<?php
if (!is_ajax()) {
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
<head>
	<title>PiePHP - <?php echo $title; ?></title>
	<link rel="stylesheet" href="/media/base.css" type="text/css">
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
	<form id="veil"></form>
	<div id="head">
		<div class="section">
			<a href="<?php echo $HTTP_ROOT; ?>" id="logo">PiePHP</a>
			<div id="user">
				<a href="<?php echo $HTTP_ROOT; ?>sign_up" class="veil"><b>Sign Up</b></a>
				<a href="<?php echo $HTTP_ROOT; ?>sign_in" class="veil"><b>Sign In</b></a>
			</div>
			<ul>
				<li><a href="<?php echo $HTTP_ROOT; ?>">Home</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>downloads">Downloads</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>tutorials">Tutorials</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>documentation">Documentation</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>forums">Forums</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>community">Community</a></li>
			</ul>
		</div>
	</div>
	<div id="body">
		<div class="section">
			<?php
			}
			else {
				?><var title="title">PiePHP - <?php echo $title; ?></var><?php
			}
			include $viewPath;
			?>
			<br id="bodyEnd">
			<?php
			if (!is_ajax()) {
			?>
		</div>
	</div>
	<div id="foot">
		<div class="section">
			<ul>
				<li><a href="<?php echo $HTTP_ROOT; ?>">Home</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>downloads">Downloads</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>tutorials">Tutorials</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>documentation">Documentation</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>forums">Forums</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>community">Community</a></li>
				<li><a href="<?php echo $HTTP_ROOT; ?>license">License</a></li>
				<?php
				if (is_localhost()) {
					?>
					<li><a href="<?php echo $HTTP_ROOT; ?>admin">Admin</a></li>
					<?php
				}
				?>
			</ul>
		</div>
	</div>
	<?php
	if ($GLOBALS['ENVIRONMENT'] == 'development') {
		?>
		<iframe id="refresher" src="<?php echo $DISPATCHER_PATH; ?>refresher" style="display:none"></iframe>
		<?php
	}
	?>
</body>
<script type="text/javascript" src="/media/jquery-1.4.2.js"></script>
<script type="text/javascript" src="/media/base.js"></script>
<script type="text/javascript" src="/media/uservoice.js"></script>
<script type="text/javascript" src="/media/google_analytics.js"></script>
</html>
<?php
}
?>