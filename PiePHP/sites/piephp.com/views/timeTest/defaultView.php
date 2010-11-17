<table>
<?php
foreach ($posts as $post) {
	?>
	<tr>
		<td><?php echo $post['id']; ?></td>
		<td><?php echo $post['title']; ?></td>
		<td><?php echo $post['body']; ?></td>
		<td><?php echo $post['created']; ?></td>
		<td><?php echo $post['modified']; ?></td>
	</tr>
	<?php
}
?>
</table>