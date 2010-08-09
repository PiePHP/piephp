<?php
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
if ($is_ajax) {
?><var>PiePHP - <?php echo $title; ?></var><?php
}
else {
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
<head>
	<title>PiePHP - <?php echo $title; ?></title>
	<?php
	if ($is_mobile) {
		?>
		<link rel="stylesheet" href="/media/mobile.css" type="text/css" />
		<?php
	}
	else {
		?>
		<link rel="stylesheet" href="/media/base.css" type="text/css" />
		<?php
	}
	?>
	<script type="text/javascript" src="/media/jquery-1.4.2.js"></script>
	<script type="text/javascript" src="/media/base.js"></script>
	<link rel="shortcut icon" href="/favicon.ico" />
</head>
<body id="<?php echo $is_mobile ? 'mobile' : ($view_name == 'home' ? 'home' : ''); ?>" class="">
	<div id="head">
		<div class="section">
			<a href="<?php echo HTTP_ROOT; ?>" id="logo">PiePHP</a>
			<ul>
				<?php
				if ($is_mobile) {
					?>
					<li><a href="<?php echo HTTP_ROOT; ?>user_guide/">User Guide</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>forums/">Forums</a></li>
					<?php
				}
				else {
					?>
					<li><a href="<?php echo HTTP_ROOT; ?>">Home</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>downloads/">Downloads</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>tutorials/">Tutorials</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>documentation/">Documentation</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>forums/">Forums</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>community/">Community</a></li>
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
			include $view_path;
			if (!$is_ajax) {
			?>
		</div>
	</div>
	<div id="foot">
		<div class="section">
			<?php
			if (!$is_mobile) {
				?>
				<ul>
					<li><a href="<?php echo HTTP_ROOT; ?>">Home</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>downloads/">Downloads</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>tutorials/">Tutorials</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>documentation/">Documentation</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>forums/">Forums</a></li>
					<li><a href="<?php echo HTTP_ROOT; ?>community/">Community</a></li>
					<?php
					if ($is_localhost || substr($_SERVER['REMOTE_ADDR'], 0, 7) == '192.168') {
						?>
						<li><a href="<?php echo HTTP_ROOT; ?>admin/">Admin</a></li>
						<li><a href="<?php echo HTTP_ROOT; ?>code_generator/">Code Generator</a></li>
						<?php
					}
					?>
				</ul>
				<?php
			}
			?>
		</div>
	</div>
	<?php
	if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || substr($_SERVER['REMOTE_ADDR'], 0, 7) == '192.168') {
		?>
		<iframe src="<?php echo HTTP_ROOT; ?>refresher" style="display:none"></iframe>
		<?php
	}
?>
</body>
</html>
<?php
}
?>