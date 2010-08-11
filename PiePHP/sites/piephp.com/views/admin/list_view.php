<h1><?php echo $title; ?></h1>
<?php $scaffold->renderList(); ?>
<a href="<?php echo HTTP_ROOT . "admin/$section/add"; ?>" class="add">Add a <?php echo $scaffold->singular; ?></a>