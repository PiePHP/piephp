<h1>Forums</h1>
<?

$i = 1 / 0;

while (list(, $row) = each($forums)) {
	?>
	<p><?=$row['id']?>: <?=$row['name']?></p>
	<?
}

?>