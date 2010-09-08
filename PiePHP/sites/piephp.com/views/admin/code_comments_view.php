<form action="<?php echo $HTTP_ROOT; ?>code_comments" method="post">
<input type="hidden" name="path" value="<?php echo htmlentities($path); ?>">
<?php echo $source; ?>
<button type="submit" class="main" style="float:right"><b>Write comments</b></button>
</form>
<script type="text/javascript">
$('#refresher').remove();
</script>