<h1>Patches</h1>
<?php

foreach ($results as $name => $patches) {
	?>
	<h3><?php echo $name ?></h3>
	<?php
	if (count($patches)) {
		foreach ($patches as $patch) {
			?>
			<?php echo $patch; ?><br>
			<?php
		}
	}
	else {
		?>
		<p>No new patches.</p>
		<?php
	}
	?>
	<br>
	<?php
}

?>