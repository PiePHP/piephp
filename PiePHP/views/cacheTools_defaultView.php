<h1>Cache tools</h1>
<p>Select a cache to view statistics.</p>
<ul>
<?php

foreach ($caches as $name => $cache) {
	?>
	<li><a href="<?php echo $HTTP_ROOT; ?>cache_tools/stats/<?php echo $name ?>"><?php echo $name ?></a></li>
	<?php
}

?>
</ul>