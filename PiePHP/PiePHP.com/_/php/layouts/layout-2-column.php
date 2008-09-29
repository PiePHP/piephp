<?php
PageHeader($pageContent);
?>
<tr>
	<td>
		<div id="side">
		<?php
		Slice('Side');
		?>
		</div>
	</td>
	<td>
		<div id="body">
		<?php
		Slice('Body');
		?>
		</div>
	</td>
</tr>
<?php
PageFooter($pageContent);
?>