<?php
PieLayout::pageHeader($pageContent);
?>
<div id="body">
<?php
PieLayout::renderSlice('Body');
?>
</div>
<?php
PieLayout::pageFooter($pageContent);
?>