<h1>PiePHP - the instant gratification framework</h1>
<h2><br>Why use PiePHP?</h2>
<p>You won't find a faster framework anywhere.</p>

<h2><br>Status</h2>
<p>PiePHP is in alpha!</p>


Description
Download
Announcements
Features



<var><?
foreach ($posts as $post) {
	?>
	<?=$post['id']?>,
	<?=$post['title']?>,
	<?=$post['body']?>,
	<?=$post['created']?>,
	<?=$post['modified']?>,
	<?
}
?></var>

