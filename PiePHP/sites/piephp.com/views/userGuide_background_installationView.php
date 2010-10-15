<div id="headingNav">
	<a href="<?php echo $URL_ROOT; ?>user_guide/background/server_requirements">&lt;&lt;</a>
	<a href="<?php echo $URL_ROOT; ?>user_guide/get_started/application_flow">&gt;&gt;</a>
</div>
<h1>Installation</h1>

<ol>
	<li class="pad">Install Apache, PHP and MySQL if you don't already have them, and ensure they work together.</li>
	<li class="pad">Download <a href="http://piephp.svn.sourceforge.net/viewvc/piephp/trunk/PiePHP/?view=tar">PiePHP from SourceForge</a>.</li>
	<li class="pad">Unzip to a directory on your machine.</li>
	<li class="pad">Point your Apache <b>DocumentRoot</b> to <b>PiePHP/sites/piephp.com/public</b>.</li>
	<li class="pad">Copy <b>PiePHP/sites/piephp.com/localConfigExample.php</b> to <b>PiePHP/sites/piephp.com/localConfig.php</b> and change the necessary parameters (like your database connection).</li>
</ol>

<p>If you would like to help us build an installer, please contact <b>sam<var>username</var>@piephp<var>domain</var>.com</b>.</p>