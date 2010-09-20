<div id="explanation">
	<div id="pie"></div>
	<h1 id="ig">
		<b>PiePHP</b> gives <b>instant gratification</b><br>
		by making web development fast<br>
		and your applications <b>even faster</b>.
	</h1>
</div>

<a id="download" href="http://piephp.svn.sourceforge.net/viewvc/piephp/trunk/PiePHP/?view=tar">
	<b id="down"></b>
	<b id="product">Download PiePHP<i>Version <?php echo $GLOBALS['VERSION']; ?></i></b>
</a>

<div id="news">
	<h2>What's new?</h2>
	<p>We're actively seeking talented contributors.  If you love getting things done quickly and painlessly, and if you have a knack for PHP and/or jQuery, then please get in touch with us to find out how you could fit on the team.</p>
</div>

<div id="features">
	<h2>What's in the pie?</h2>
	<ul>
		<li>A simple yet solid <b>Model View Controller</b> architecture</li>
		<li>Essential <b>rapid application development</b> features</li>
	</ul>
	<br>
	<h2>What sets PiePHP apart?</h2>
	<ul>
		<li><b>Speed</b><div>PiePHP has the fastest page rendering time of any major PHP framework.</div></li>
		<li><b>Environments</b><div>PiePHP supports development, test, staging and production environments to make your development environment flexible and your live site secure.</div></li>
		<li><b>Auto-refresh</b><div>When you save changes in your IDE, your PiePHP development environment can cause your pages to refresh in every browser window.  What better way to optimize the change &amp; verify cycle?</div></li>
		<li><b>Error editing</b><div>In a development environment, any error can show an editable code block for each step of the stack trace.  So you can fix the error and move on quickly without even using your editor.</div></li>
		<li><a href="<?php echo $HTTP_ROOT; ?>documentation">Much much more...</a></li>
	</ul>
</div>



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

