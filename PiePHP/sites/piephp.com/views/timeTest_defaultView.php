<?php
foreach ($posts as $post) {
	?>
	<?php echo $post['id']; ?>,
	<?php echo $post['title']; ?>,
	<?php echo $post['body']; ?>,
	<?php echo $post['created']; ?>,
	<?php echo $post['modified']; ?>,
	<?php
}
?>