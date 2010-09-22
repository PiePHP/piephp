<h1>Database patches</h1>
<p>The following patches were run:</p>
<?php

foreach ($results as $name => $result) {
	?>
	<h3><?php echo $name ?></h3>
	<?php
	if (!count($result)) {
		?>
		<p>There were no new patches.</p>
		<?php
	}
	?>
	<br>
	<?php
}

?>