<div id="explanation">
	<div id="pie"></div>
	<h1 id="ig">
		<b>PiePHP</b> gives <b>instant gratification</b><br>
		by making web development fast<br>
		and your websites <b>even faster</b>.
	</h1>
</div>

<a id="download" href="http://piephp.svn.sourceforge.net/viewvc/piephp/trunk/PiePHP/?view=tar">
	<b id="down"></b>
	<b id="product">Download PiePHP<i>Version <?php echo $GLOBALS['VERSION']; ?></i></b>
</a>

<div id="fb">
	<?php echo '<fb:like width="282"></fb:like>'; ?>
</div>

<iframe id="mrec" class="9402400169653768/0109456368" frameborder="0" scrolling="no"></iframe>

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
		<li><b>Persistent page</b><div>The PiePHP JavaScript library can AJAXify your links and forms, so the page shell doesn't need to re-render as users browse. That means you can put media players or chat widgets or whatever you want into your page, and they won't need to stop.</div></li>
		<li><b>Environments</b><div>PiePHP supports development, test, staging and production environments to make your development environment flexible and your live site secure.</div></li>
		<li><b>Auto-refresh</b><div>When you save changes in your IDE, your PiePHP development environment can cause your pages to refresh in every browser window.  What better way to optimize the change &amp; verify cycle?</div></li>
		<li><b>Error editing</b><div>In a development environment, errors are shown as editable code blocks.  So you can fix the error and move on quickly without even leaving the page.</div></li>
	</ul>
</div>

<div id="news">
	<h2>What's new?</h2>
	<p>We're actively seeking talented contributors.  If you love getting things done quickly and painlessly, and if you have a knack for PHP and/or jQuery, then please get in touch with us to find out how you can be a part of the team.</p>
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

