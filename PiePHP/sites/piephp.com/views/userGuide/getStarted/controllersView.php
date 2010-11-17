<div id="headingNav">
	<a href="<?php echo $URL_ROOT; ?>user_guide/get_started/directory_structure">&lt;</a>
	<a href="<?php echo $URL_ROOT; ?>user_guide/get_started/models">&gt;</a>
</div>

<h1>Controllers</h1>

<p>Controllers are classes which process requests and render responses.</p>  

<h2>Naming</h2>

<p>Controller names are capitalized using <a href="http://en.wikipedia.org/wiki/CamelCase">upper camel case</a>, and they must end with "Controller".  For example the <b>/user_guide/</b> section of this site is controlled by the <b>UserGuideController</b> which is stored in <b>sites/piephp.com/classes/UserGuideController.php</b>.  All controllers must follow this naming scheme and be stored in the classes folder.</p>

