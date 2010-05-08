<?
require $_SERVER['DOCUMENT_ROOT'] . '/../initialization/common.php';
Pie::file(PIE_ROOT . '/libraries/PieGenerator.class.php');
?>
<script type="text/javascript" src="/_/js/generator.js"></script>

<h1>Pie Interface Generator</h1>

<form action="" method="post">
<?
PieGenerator::edit();
?>
</form>

<?
PieLayout::renderPage();
?>
