<div id="headingNav">
	<a href="<?php echo $URL_ROOT; ?>user_guide/get_started/url_routing">&lt;&lt;</a>
	<a href="<?php echo $URL_ROOT; ?>user_guide/get_started/controllers">&gt;&gt;</a>
</div>

<h1>Directory structure</h1>
<p>PiePHP can share classes and views between multiple sites, and the directory structure is set up to hold those classes, views and sites.</p>
<p>After downloading the latest version of PiePHP and unzipping it, you will have the following directory structure:</p>

<ul>
	<li>
		<b>classes</b> - PiePHP classes that can be shared between sites
	</li>
	<li>
		<b>setup</b> - A setup script for creating new sites
	</li>
	<li>
		<b>sites</b> - Multiple PiePHP sites on a single server
		<ul>
			<li>
				<b>piephp.com</b> - An example PiePHP site
				<ul>
					<li>
						<b>cache</b> - Files that have been rendered by the server and stored for fast access
					</li>
					<li>
						<b>classes</b> - Classes used by a specific site
					</li>
					<li>
						<b>logs</b> - Log files, written to by a specific site
					</li>
					<li>
						<b>patches</b> - Database patches that can be run in sequence to build the site's database
					</li>
					<li>
						<b>public</b> - The Apache DocumentRoot for your site
					</li>
					<li>
						<b>templates</b> - Shells for your views
					</li>
					<li>
						<b>views</b> - PHP pages that will display content from your controllers and models
					</li>
				</ul>
			</li>
		</ul>
	</li>
	<li>
		<b>views</b> - PHP pages that will display content from controllers that are shared between sites
	</li>
</ul>