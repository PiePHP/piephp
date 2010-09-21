<h1>Search</h1>
<?php
if (isset($_REQUEST['q'])) {
	?>
	<p>You searched for "<?php echo $_REQUEST['q']; ?>".</p>
	<?php
}
?>
<p>When we have a search engine, the results will go here.</p>
