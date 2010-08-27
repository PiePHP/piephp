<?php
$fh = fopen('urls.log', 'a');
fwrite($fh, $_REQUEST['id'] . "\n");
fclose($fh);
