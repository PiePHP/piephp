<h1>Application flow</h1>

<style>
.diagram {
	border: 1px solid #8af;
	padding: 20px;
	border-radius: 20px;
	float: right;
	margin: 0 0 20px 20px;
}
</style>

<img src="<?php echo $URL_ROOT; ?>img/userGuide/flow.gif" width="373" height="303" class="diagram" alt="">
<h2>For non-caching requests...</h2>
<p>A basic PiePHP request starts with the dispatcher, which is the index.php file in your site's public directory.  The dispatcher resolves the URL to a specific controller.  The controller may fetch data from one or more models.  Then the controller renders data through a template and view, which send output to the dispatcher.</p>
<br class="clear"><br><br>

<img src="<?php echo $URL_ROOT; ?>img/userGuide/cacheFlow.gif" width="436" height="303" class="diagram" alt="">
<h2>For caching requests...</h2>
<p>Some controllers are set to cache their output for future reuse.  When this is the case, the dispatcher writes the final output to a cache, which is a type of model that can use files or memcached. The next request for the same page can just return data from the cache.</p>
<br class="clear"><br><br>

<img src="<?php echo $URL_ROOT; ?>img/userGuide/cachedFlow.gif" width="317" height="43" class="diagram" alt="">
<h2>For cached requests...</h2>
<p>When the dispatcher finds a cache entry that corresponds to the requested URL and parameters, it returns cached data without needing to invoke any controllers or models. In PiePHP, this process is extremely fast.</p>
<br class="clear"><br><br>
