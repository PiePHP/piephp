<h1>Database patches</h1>
<p>The following patches were run:</p>
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