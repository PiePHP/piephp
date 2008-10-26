<?php
require $_SERVER['DOCUMENT_ROOT'].'/../app-init/common.php';
?>

<h1>To Do</h1>

<br>

<h2>Server Tools</h2>

<br>

<h3>Server Configurer</h3>
<ul>
	<li>Set up Apache configuration</li>
	<li>Set up file permissions</li>
	<li>Set up DB connection</li>
	<li>Set up Memcache connection</li>
	<li>Set up user accounts for server tools</li>
</ul>

<br>

<h3>Site Creation Wizard</h3>
<ul>
	<li>Select modules</li>
	<li>Build navigation</li>
	<li>Select template</li>
	<li>Select colors &amp; fonts</li>
	<li>Create database</li>
	<li>Create files</li>
</ul>

<br>

<br>

<h2>Site Tools</h2>

<br>

<h3>Auto-Refresher</h3>
<ul>
	<li>Refresh the page when files change</li>
	<li>Include CSS, JS &amp; images</li>
</ul>

<br>

<h3>Log Watcher</h3>
<ul>
	<li>Watch a customizable set of PHP logs</li>
	<li>Watch JS logging</li>
</ul>

<br>

<h3>Test Suite</h3>
<ul>
	<li>In "development" environment, run tests that are new/modified or that use modified files.</li>
	<li>In "test" environment, run tests when a new revision is found, starting with new/modified.</li>
</ul>

<br>

<h3>Navigation Manager</h3>
<ul>
	<li>Edit navigation with a tree-based control that respects node order.</li>
	<li>Cache the navigation for each possible combination of user groups (or just use Memcache).</li>
</ul>

<br>

<h3>Page Manager</h3>
<ul>
	<li>Edit title, description &amp; content (possibly dynamic content).</li>
	<li>Edit permissions.</li>
</ul>

<br>

<h3>Interface Builder</h3>
<ul>
	<li>Select actions.</li>
	<li>Select fields.</li>
	<li>Create/alter database table(s) &amp; schema files.</li>
	<li>Write controller &amp; action files.</li>
</ul>

<br>

<br>

<h2>Modules</h2>

<br>

<h3>Project Planner</h3>
<ul>
	<li>Project Management interface with iterations &amp; n-level task nesting</li>
	<li>Bug system tied to project tasks</li>
</ul>

<br>

<h3>Store</h3>
<ul>
	<li>Searchable store with n-level category nesting</li>
	<li>Shopping cart system (with Google Checkout integration?)</li>
</ul>

<br>

<?php
PieLayout::renderPage();
?>
