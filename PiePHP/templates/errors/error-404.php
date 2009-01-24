<?
require $_SERVER['DOCUMENT_ROOT'] . '/../initialization/common.php';
?>

<h1>Page Not Found</h1>
<br>
<p>
<?
	echo $_SERVER['REQUEST_URI'];
?>
</p>

<?
PieLayout::renderPage();
?>
