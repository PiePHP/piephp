<?php
$urls = array(
	'google' => 'http://www.google.com/',
	'facebook' => 'http://www.facebook.com/',
	'youtube' => 'http://www.youtube.com/',
	'yahoo' => 'http://www.yahoo.com/',
	'live' => 'http://www.live.com/',
	'baidu' => 'http://www.baidu.com/',
	'wikipedia' => 'http://www.wikipedia.org/',
	'blogger' => 'http://www.blogger.com/',
	'msn' => 'http://www.msn.com/',
	'qq' => 'http://www.qq.com/',
	'twitter' => 'http://www.twitter.com/'
);
?>
<html>
<head>
	<title>Where have you been?</title>
</head>
<style>
<?php
reset($urls);
while (list($id, $url) = each($urls)) {
	?>
	<?php echo '#' . $id; ?>:visited{background-image:url(bg.php?id=<?php $id; ?>)}
	<?php
}
?>
</style>
<body>
<?php
reset($urls);
while (list($id, $url) = each($urls)) {
	?>
	<a href="<?php $url; ?>" id="<?php $id; ?>"><?php $id; ?></a>
	<?php
}
?>
</body>
</html>
