

<div id="log"></div>
<?
for ($i = 1; $i <= 10000; $i++) {
	echo '<a href="#" class="link'.($i % 10).'">'.$i.'</a> &nbsp; ';
}
?>
<script src="/js/prototype-1.6.0.2.js"></script>
<script>
var startTime = (new Date()).getTime();
$$('a').each(function(element) {
	element.style.color = '#F00';
});
$('log').insert('time: ' + ((new Date()).getTime() - startTime));
</script>