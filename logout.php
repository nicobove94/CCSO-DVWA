<?php

define( 'CCSO_WEB_PAGE_TO_ROOT', '' );
require_once CCSO_WEB_PAGE_TO_ROOT . 'ccso/includes/ccsoPage.inc.php';

ccsoPageStartup( array( 'phpids' ) );

if( !ccsoIsLoggedIn() ) {	// The user shouldn't even be on this page
	// ccsoMessagePush( "You were not logged in" );
	ccsoRedirect( 'login.php' );
}

ccsoLogout();
ccsoMessagePush( "You have logged out" );
ccsoRedirect( 'login.php' );

?>
