<div id="headingNav">
	<a href="<?php echo $URL_ROOT; ?>user_guide/get_started/url_routing">&lt;</a>
	<a href="<?php echo $URL_ROOT; ?>user_guide/get_started/controllers">&gt;</a>
</div>

<style>
.folder {
	margin-bottom: 14px;
	background: url(/img/userGuide/folder.png) no-repeat 0 -24px;
	height: 24px;
	padding-left: 34px;
	font-weight: bold;
	font-size: 14px;
}

.folder i {
	display: block;
	font-size: 11px;
	font-style: normal;
	font-weight: normal;
	color: #666;
}

.indent {
	background-position: 34px 0;
	padding-left: 68px;
}

.structure {
	background: #eef;
	padding: 20px;
	border: 1px solid #bce;
	border-radius: 20px;
}
</style>

<h1>Directory structure</h1>

<p>PiePHP can share classes and views between multiple sites, and the directory structure is set up to hold those classes, views and sites.</p>

<p>After downloading the latest version of PiePHP and unzipping it, you will have the following directory structure:</p>

<div class="structure">
	<div class="folder">PiePHP<i>The root of your PiePHP installation</i></div>
	<div class="folder indent">classes<i>PiePHP classes that can be shared between sites</i></div>
	<div class="folder indent">sites<i>Multiple PiePHP sites on a single server</i></div>
	<div class="folder indent">views<i>PHP views that can be shared between sites</i></div>
</div>

<p>Inside the sites directory, you will have a copy of piephp.com to use as an example site:</p>

<div class="structure">
	<div class="folder">piephp.com<i>An example PiePHP site</i></div>
	<div class="folder indent">data<i>Cached data, logs, database patches, etc.</i></div>
	<div class="folder indent">classes<i>Classes used by a specific site</i></div>
	<div class="folder indent">public<i>The DocumentRoot for your site</i></div>
	<div class="folder indent">templates<i>Template files that will include your views</i></div>
	<div class="folder indent">views<i>PHP views that can be shared between sites</i></div>
</div>