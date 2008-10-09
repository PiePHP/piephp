<?php
require $_SERVER['DOCUMENT_ROOT'].'/../app-init/common.php';
?>

<h1>Page Not Found</h1>
<br>
<p>
<?php
	echo $_SERVER['REQUEST_URI'];
?>
</p>

<?php
PieLayout::renderPage();
?>
