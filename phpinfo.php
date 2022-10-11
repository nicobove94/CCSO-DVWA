<?php

define( 'CCSO_WEB_PAGE_TO_ROOT', '' );
require_once CCSO_WEB_PAGE_TO_ROOT . 'ccso/includes/ccsoPage.inc.php';

dvwaPageStartup( array( 'authenticated', 'phpids' ) );

phpinfo();

?>
