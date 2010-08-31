<?php
if (is_ajax()) {
?><var title="title">PiePHP - <?php echo $title; ?></var><?php
}
else {
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
<head>
	<title>PiePHP - <?php echo $title; ?></title>
	<?php
	if (is_mobile()) {
		?>
		<link rel="stylesheet" href="/media/mobile.css" type="text/css">
		<?php
	}
	else {
		?>
		<link rel="stylesheet" href="/media/base.css" type="text/css">
		<?php
	}
	?>
	<script type="text/javascript" src="/media/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="/media/base.js"></script>
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body id="<?php echo is_mobile() ? 'mobile' : ($viewName == 'home' ? 'home' : ''); ?>">
	<form id="veil"></form>
	<div id="head">
		<div class="section">
			<a href="<?php echo $HTTP_ROOT; ?>" id="logo">PiePHP</a>
			<ul>
				<?php
				if (is_mobile()) {
					?>
					<li><a href="<?php echo $HTTP_ROOT; ?>user_guide/">User Guide</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>forums/">Forums</a></li>
					<?php
				}
				else {
					?>
					<li><a href="<?php echo $HTTP_ROOT; ?>admin/users/">Users</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>sign_in/" class="veil">Sign In</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>">Home</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>downloads/">Downloads</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>tutorials/">Tutorials</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>documentation/">Documentation</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>forums/">Forums</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>community/">Community</a></li>
					<?php
				}
				?>
			</ul>
		</div>
	</div>
	<div id="body">
		<div class="section">
			<?php
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
			<?php
			if (!is_mobile()) {
				?>
				<ul>
					<li><a href="<?php echo $HTTP_ROOT; ?>">Home</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>downloads/">Downloads</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>tutorials/">Tutorials</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>documentation/">Documentation</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>forums/">Forums</a></li>
					<li><a href="<?php echo $HTTP_ROOT; ?>community/">Community</a></li>
					<?php
					if (is_localhost()) {
						?>
						<li><a href="<?php echo $HTTP_ROOT; ?>code_generator/">Code Generator</a></li>
						<li><a href="<?php echo $HTTP_ROOT; ?>admin/">Admin</a></li>
						<li><a href="<?php echo $HTTP_ROOT; ?>phpinfo/">PHPinfo</a></li>
						<?php
					}
					?>
				</ul>
				<?php
			}
			?>
		</div>
	</div>
	<?php $this->renderRefresher() ?>
</body>
</html>
<?php
}
?>