<div id="headingNav">
	<a href="<?php echo $URL_ROOT; ?>user_guide/background/installation">&lt;&lt;</a>
	<a href="<?php echo $URL_ROOT; ?>user_guide/get_started/url_routing">&gt;&gt;</a>
</div>

<h1>Application flow</h1>

<style>
#diagram {
	background: #000;
	padding: 20px;
	border-radius: 20px;
	float: right;
	margin: 0 0 20px 20px;
}
</style>
<img src="/img/userGuide/flow.gif" width="240" height="459" id="diagram" alt="">

<p><b>PiePHP requests typically work like this:</b></p>
<ol>
	<li class="pad">A user requests a page.</li>
	<li class="pad">The dispatcher (index.php) searches the cache for a response.</li>
	<li class="pad">If there's a match in the cache, the dispatcher can skip to step 11.</li>
	<li class="pad">The dispatcher routes the request to the appropriate controller.</li>
	<li class="pad">The controller can load models.</li>
	<li class="pad">Models can return data to the controller.</li>
	<li class="pad">The controller passes its data to a template and view for rendering.</li>
	<li class="pad">The template encapsulates the view.</li>
	<li class="pad">The rendered output is received by the dispatcher.</li>
	<li class="pad">If the controller allows it, the output is cached for next time.</li>
	<li class="pad">The response is sent.</li>
</ol>