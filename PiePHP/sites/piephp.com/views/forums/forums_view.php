<h1>Forums</h1>
<?

while (list(, $row) = each($forums)) {
	?>
	<p><?=$row['id']?>: <?=$row['name']?></p>
	<?
}

?>