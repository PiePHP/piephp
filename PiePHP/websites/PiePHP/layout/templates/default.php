<?php
PieLayout::pageHeader($pageContent);
?>
<div id="body">
<?php
PieLayout::renderSlice('body');
?>
</div>
<?php
PieLayout::pageFooter($pageContent);
?>