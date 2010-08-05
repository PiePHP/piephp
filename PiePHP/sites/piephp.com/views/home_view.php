<h1>PiePHP is the simplest Web Application Framework on the planet</h1>

<h2><br>Why use PiePHP?</h2>
<p>You won't find a faster framework anywhere.</p>

<h2><br>Status</h2>
<p>PiePHP is in alpha!</p>

<?
foreach ($posts as $post) {
	?>
	<?=$post['id']?>,
	<?=$post['title']?>,
	<?=$post['body']?>,
	<?=$post['created']?>,
	<?=$post['modified']?>,
	<?=$post['id']?>,
	<?=$post['id']?>,
	<?
}
?>