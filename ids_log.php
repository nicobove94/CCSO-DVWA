<?php

define( 'CCSO_WEB_PAGE_TO_ROOT', '' );
require_once CCSO_WEB_PAGE_TO_ROOT . 'ccso/includes/ccsoPage.inc.php';

define( 'CCSO_WEB_ROOT_TO_PHPIDS_LOG', 'external/phpids/' . ccsoPhpIdsVersionGet() . '/lib/IDS/tmp/phpids_log.txt' );
define( 'CCSO_WEB_PAGE_TO_PHPIDS_LOG', CCSO_WEB_PAGE_TO_ROOT.CCSO_WEB_ROOT_TO_PHPIDS_LOG );

ccsoPageStartup( array( 'authenticated', 'phpids' ) );

$page = ccsoPageNewGrab();
$page[ 'title' ]   = 'PHPIDS Log' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'log';
// $page[ 'clear_log' ]; <- Was showing error.

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>PHPIDS Log</h1>

	<p>" . ccsoReadIdsLog() . "</p>
	<br /><br />

	<form action=\"#\" method=\"GET\">
		<input type=\"submit\" value=\"Clear Log\" name=\"clear_log\">
	</form>

	" . ccsoClearIdsLog() . "
</div>";

ccsoHtmlEcho( $page );

?>
