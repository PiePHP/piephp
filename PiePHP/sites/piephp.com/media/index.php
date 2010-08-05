<?
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
<?
reset($urls);
while (list($id, $url) = each($urls)) {
	?>
	#<?=$id?>:visited{background-image:url(bg.php?id=<?=$id?>)}
	<?
}
?>
</style>
<body>
<?
reset($urls);
while (list($id, $url) = each($urls)) {
	?>
	<a href="<?=$url?>" id="<?=$id?>"><?=$id?></a>
	<?
}
?>
</body>
</html>
