<?php

$ENVIRONMENT = 'development';

$DATABASES['default'] = 'mysql:host=localhost username=YOUR_USERNAME password=YOUR_PASSWORD database=piephp';
$CACHES['default'] = 'file:expire=10';
$CACHES['pages'] = 'file:prefix=piephp_pages_ expire=10';

$URL_ROOT = '/index.php/';

$REFRESHER_FILE = 'THE_PATH_TO_A_FILE_THAT_YOUR_EDITOR_TOUCHES_WHEN_IT_SAVES_A_FILE';

$PACKAGE = 'PiePHP';
$COPYRIGHT = 'Copyright (c) 2007-2010, Pie Software Foundation';
$AUTHOR = 'YOUR_NAME <YOUR_EMAIL>';
$LICENSE = 'http://www.piephp.com/license';

$SALT = 'A_SECRET_STRING_FOR_YOUR_SESSION_SECURITY';
