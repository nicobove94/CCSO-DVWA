<?php
// Status- Basically done
if( !defined( 'CCSO_WEB_PAGE_TO_ROOT' ) ) {
	die( 'CCSO System error- WEB_PAGE_TO_ROOT undefined' );
	exit;
}

session_start(); // Creates a 'Full Path Disclosure' vuln.

if (!file_exists(CCSO_WEB_PAGE_TO_ROOT . 'config/config.inc.php')) {
	die ("CCSO System error - config file not found. Copy config/config.inc.php.dist to config/config.inc.php and configure to your environment.");
}

// Include configs
require_once CCSO_WEB_PAGE_TO_ROOT . 'config/config.inc.php';
require_once( 'ccsoPhpIds.inc.php' );

// Declare the $html variable
if( !isset( $html ) ) {
	$html = "";
}

// Valid security levels
$security_levels = array('low', 'medium', 'high', 'impossible');
if( !isset( $_COOKIE[ 'security' ] ) || !in_array( $_COOKIE[ 'security' ], $security_levels ) ) {
	// Set security cookie to impossible if no cookie exists
	if( in_array( $_CCSO[ 'default_security_level' ], $security_levels) ) {
		ccsoSecurityLevelSet( $_CCSO[ 'default_security_level' ] );
	}
	else {
		ccsoSecurityLevelSet( 'impossible' );
	}

	if( $_CCSO[ 'default_phpids_level' ] == 'enabled' )
		ccsoPhpIdsEnabledSet( true );
	else
		ccsoPhpIdsEnabledSet( false );
}

if (!array_key_exists ("default_locale", $_CCSO)) {
	$_CCSO[ 'default_locale' ] = "en";
}

ccsoLocaleSet( $_CCSO[ 'default_locale' ] );

// CCSO version
function ccsoVersionGet() {
	return '1.10 *Development*';
}

// CCSO release date
function ccsoReleaseDateGet() {
	return '2015-10-08';
}


// Start session functions --

function &ccsoSessionGrab() {
	if( !isset( $_SESSION[ 'ccso' ] ) ) {
		$_SESSION[ 'ccso' ] = array();
	}
	return $_SESSION[ 'ccso' ];
}


function ccsoPageStartup( $pActions ) {
	if( in_array( 'authenticated', $pActions ) ) {
		if( !ccsoIsLoggedIn()) {
			ccsoRedirect( CCSO_WEB_PAGE_TO_ROOT . 'login.php' );
		}
	}

	if( in_array( 'phpids', $pActions ) ) {
		if( ccsoPhpIdsIsEnabled() ) {
			ccsoPhpIdsTrap();
		}
	}
}


function ccsoPhpIdsEnabledSet( $pEnabled ) {
	$ccsoSession =& ccsoSessionGrab();
	if( $pEnabled ) {
		$ccsoSession[ 'php_ids' ] = 'enabled';
	}
	else {
		unset( $ccsoSession[ 'php_ids' ] );
	}
}


function ccsoPhpIdsIsEnabled() {
	$ccsoSession =& ccsoSessionGrab();
	return isset( $ccsoSession[ 'php_ids' ] );
}


function ccsoLogin( $pUsername ) {
	$ccsoSession =& ccsoSessionGrab();
	$ccsoSession[ 'username' ] = $pUsername;
}


function ccsoIsLoggedIn() {
	$ccsoSession =& ccsoSessionGrab();
	return isset( $ccsoSession[ 'username' ] );
}


function ccsoLogout() {
	$ccsoSession =& ccsoSessionGrab();
	unset( $ccsoSession[ 'username' ] );
}


function ccsoPageReload() {
	ccsoRedirect( $_SERVER[ 'PHP_SELF' ] );
}

function ccsoCurrentUser() {
	$ccsoSession =& ccsoSessionGrab();
	return ( isset( $ccsoSession[ 'username' ]) ? $ccsoSession[ 'username' ] : '') ;
}

// -- END (Session functions)

function &ccsoPageNewGrab() {
	$returnArray = array(
		'title'           => 'Competitive Cyber Security Organization (CCSO) v' . ccsoVersionGet() . '',
		'title_separator' => ' :: ',
		'body'            => '',
		'page_id'         => '',
		'help_button'     => '',
		'source_button'   => '',
	);
	return $returnArray;
}


function ccsoSecurityLevelGet() {
	return isset( $_COOKIE[ 'security' ] ) ? $_COOKIE[ 'security' ] : 'impossible';
}


function ccsoSecurityLevelSet( $pSecurityLevel ) {
	if( $pSecurityLevel == 'impossible' ) {
		$httponly = true;
	}
	else {
		$httponly = false;
	}
	setcookie( session_name(), session_id(), 0, '/', "", false, $httponly );
	setcookie( 'security', $pSecurityLevel, 0, "/", "", false, $httponly );
}

function ccsoLocaleGet() {	
	$ccsoSession =& ccsoSessionGrab();
	return $ccsoSession[ 'locale' ];
}

function ccsoSQLiDBGet() {
	global $_CCSO;
	return $_CCSO['SQLI_DB'];
}

function ccsoLocaleSet( $pLocale ) {
	$ccsoSession =& ccsoSessionGrab();
	$locales = array('en', 'zh');
	if( in_array( $pLocale, $locales) ) {
		$ccsoSession[ 'locale' ] = $pLocale;
	} else {
		$ccsoSession[ 'locale' ] = 'en';
	}
}

// Start message functions --

function ccsoMessagePush( $pMessage ) {
	$ccsoSession =& ccsoSessionGrab();
	if( !isset( $ccsoSession[ 'messages' ] ) ) {
		$ccsoSession[ 'messages' ] = array();
	}
	$ccsoSession[ 'messages' ][] = $pMessage;
}


function ccsoMessagePop() {
	$ccsoSession =& ccsoSessionGrab();
	if( !isset( $ccsoSession[ 'messages' ] ) || count( $ccsoSession[ 'messages' ] ) == 0 ) {
		return false;
	}
	return array_shift( $ccsoSession[ 'messages' ] );
}


function messagesPopAllToHtml() {
	$messagesHtml = '';
	while( $message = ccsoMessagePop() ) {   // TODO- sharpen!
		$messagesHtml .= "<div class=\"message\">{$message}</div>";
	}

	return $messagesHtml;
}

// --END (message functions)

function ccsoHtmlEcho( $pPage ) {
	$menuBlocks = array();

	$menuBlocks[ 'home' ] = array();
	if( ccsoIsLoggedIn() ) {
		$menuBlocks[ 'home' ][] = array( 'id' => 'home', 'name' => 'Home', 'url' => '.' );
		$menuBlocks[ 'home' ][] = array( 'id' => 'instructions', 'name' => 'Instructions', 'url' => 'instructions.php' );
		$menuBlocks[ 'home' ][] = array( 'id' => 'setup', 'name' => 'Setup / Reset DB', 'url' => 'setup.php' );
	}
	else {
		$menuBlocks[ 'home' ][] = array( 'id' => 'setup', 'name' => 'Setup CCSO', 'url' => 'setup.php' );
		$menuBlocks[ 'home' ][] = array( 'id' => 'instructions', 'name' => 'Instructions', 'url' => 'instructions.php' );
	}
# Consider commenting the unnecessary ones out. You only need:
# Command Injection
# File Inclusion
# Cross-site Scripting
# File Upload
# SQL Injection
	if( ccsoIsLoggedIn() ) {
		$menuBlocks[ 'vulnerabilities' ] = array();
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'brute', 'name' => 'Brute Force', 'url' => 'vulnerabilities/brute/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'exec', 'name' => 'Command Injection', 'url' => 'vulnerabilities/exec/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'csrf', 'name' => 'CSRF', 'url' => 'vulnerabilities/csrf/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'fi', 'name' => 'File Inclusion', 'url' => 'vulnerabilities/fi/.?page=include.php' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'upload', 'name' => 'File Upload', 'url' => 'vulnerabilities/upload/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'captcha', 'name' => 'Insecure CAPTCHA', 'url' => 'vulnerabilities/captcha/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'sqli', 'name' => 'SQL Injection', 'url' => 'vulnerabilities/sqli/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'sqli_blind', 'name' => 'SQL Injection (Blind)', 'url' => 'vulnerabilities/sqli_blind/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'weak_id', 'name' => 'Weak Session IDs', 'url' => 'vulnerabilities/weak_id/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_d', 'name' => 'XSS (DOM)', 'url' => 'vulnerabilities/xss_d/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_r', 'name' => 'XSS (Reflected)', 'url' => 'vulnerabilities/xss_r/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'xss_s', 'name' => 'XSS (Stored)', 'url' => 'vulnerabilities/xss_s/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'csp', 'name' => 'CSP Bypass', 'url' => 'vulnerabilities/csp/' );
		$menuBlocks[ 'vulnerabilities' ][] = array( 'id' => 'javascript', 'name' => 'JavaScript', 'url' => 'vulnerabilities/javascript/' );
	}

	$menuBlocks[ 'meta' ] = array();
	if( ccsoIsLoggedIn() ) {
		$menuBlocks[ 'meta' ][] = array( 'id' => 'security', 'name' => 'CCSO Security', 'url' => 'security.php' );
		$menuBlocks[ 'meta' ][] = array( 'id' => 'phpinfo', 'name' => 'PHP Info', 'url' => 'phpinfo.php' );
	}
	$menuBlocks[ 'meta' ][] = array( 'id' => 'about', 'name' => 'About', 'url' => 'about.php' );

	if( ccsoIsLoggedIn() ) {
		$menuBlocks[ 'logout' ] = array();
		$menuBlocks[ 'logout' ][] = array( 'id' => 'logout', 'name' => 'Logout', 'url' => 'logout.php' );
	}

	$menuHtml = '';

	foreach( $menuBlocks as $menuBlock ) {
		$menuBlockHtml = '';
		foreach( $menuBlock as $menuItem ) {
			$selectedClass = ( $menuItem[ 'id' ] == $pPage[ 'page_id' ] ) ? 'selected' : '';
			$fixedUrl = CCSO_WEB_PAGE_TO_ROOT.$menuItem[ 'url' ];
			$menuBlockHtml .= "<li class=\"{$selectedClass}\"><a href=\"{$fixedUrl}\">{$menuItem[ 'name' ]}</a></li>\n";
		}
		$menuHtml .= "<ul class=\"menuBlocks\">{$menuBlockHtml}</ul>";
	}

	// Get security cookie --
	$securityLevelHtml = '';
	switch( ccsoSecurityLevelGet() ) {
		case 'low':
			$securityLevelHtml = 'low';
			break;
		case 'medium':
			$securityLevelHtml = 'medium';
			break;
		case 'high':
			$securityLevelHtml = 'high';
			break;
		default:
			$securityLevelHtml = 'impossible';
			break;
	}
	// -- END (security cookie)

	$phpIdsHtml   = '<em>PHPIDS:</em> ' . ( ccsoPhpIdsIsEnabled() ? 'enabled' : 'disabled' );
	$userInfoHtml = '<em>Username:</em> ' . ( ccsoCurrentUser() );
	$securityLevelHtml = "<em>Security Level:</em> {$securityLevelHtml}";
	$localeHtml = '<em>Locale:</em> ' . ( ccsoLocaleGet() );
	$sqliDbHtml = '<em>SQLi DB:</em> ' . ( ccsoSQLiDBGet() );
	

	$messagesHtml = messagesPopAllToHtml();
	if( $messagesHtml ) {
		$messagesHtml = "<div class=\"body_padded\">{$messagesHtml}</div>";
	}
 # Generate keys at some point

	$systemInfoHtml = "";
	if( ccsoIsLoggedIn() ) 
		$systemInfoHtml = "<div align=\"left\">{$userInfoHtml}<br />{$securityLevelHtml}<br />{$localeHtml}<br />{$phpIdsHtml}<br />{$sqliDbHtml}</div>";
	if( $pPage[ 'source_button' ] ) {
		$systemInfoHtml = ccsoButtonSourceHtmlGet( $pPage[ 'source_button' ] ) . " $systemInfoHtml";
	}
	if( $pPage[ 'help_button' ] ) {
		$systemInfoHtml = ccsoButtonHelpHtmlGet( $pPage[ 'help_button' ] ) . " $systemInfoHtml";
	}

	// Send Headers + main HTML code
	Header( 'Cache-Control: no-cache, must-revalidate');   // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );     // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );    // Date in the past

	echo "<!DOCTYPE html>

<html lang=\"en-GB\">

	<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CCSO_WEB_PAGE_TO_ROOT . "ccso/css/main.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . CCSO_WEB_PAGE_TO_ROOT . "favicon.ico\" />

		<script type=\"text/javascript\" src=\"" . CCSO_WEB_PAGE_TO_ROOT . "ccso/js/ccsoPage.js\"></script>

	</head>

	<body class=\"home\">
		<div id=\"container\">

			<div id=\"header\">

<!-- Still need to change the image to CCSO logo-->
				<img src=\"" . CCSO_WEB_PAGE_TO_ROOT . "ccso/images/logo.png\" alt=\"Competitive Cyber Security Organization" />

			</div>

			<div id=\"main_menu\">

				<div id=\"main_menu_padded\">
				{$menuHtml}
				</div>

			</div>

			<div id=\"main_body\">

				{$pPage[ 'body' ]}
				<br /><br />
				{$messagesHtml}

			</div>

			<div class=\"clear\">
			</div>

			<div id=\"system_info\">
				{$systemInfoHtml}
			</div>

			<div id=\"footer\">

				<p>Competitive Cyber Security Organization (CCSO) v" . ccsoVersionGet() . "</p>
				<script src='" . CCSO_WEB_PAGE_TO_ROOT . "/ccso/js/add_event_listeners.js'></script>

			</div>

		</div>

	</body>

</html>";
}


function ccsoHelpHtmlEcho( $pPage ) {
	// Send Headers
	Header( 'Cache-Control: no-cache, must-revalidate');   // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );     // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );    // Date in the past

	echo "<!DOCTYPE html>

<html lang=\"en-GB\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CCSO_WEB_PAGE_TO_ROOT . "ccso/css/help.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . CCSO_WEB_PAGE_TO_ROOT . "favicon.ico\" />

	</head>

	<body>

	<div id=\"container\">

			{$pPage[ 'body' ]}

		</div>

	</body>

</html>";
}


function ccsoSourceHtmlEcho( $pPage ) {
	// Send Headers
	Header( 'Cache-Control: no-cache, must-revalidate');   // HTTP/1.1
	Header( 'Content-Type: text/html;charset=utf-8' );     // TODO- proper XHTML headers...
	Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );    // Date in the past

	echo "<!DOCTYPE html>

<html lang=\"en-GB\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>{$pPage[ 'title' ]}</title>

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . CCSO_WEB_PAGE_TO_ROOT . "ccso/css/source.css\" />

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . CCSO_WEB_PAGE_TO_ROOT . "favicon.ico\" />

	</head>

	<body>

		<div id=\"container\">

			{$pPage[ 'body' ]}

		</div>

	</body>

</html>";
}

// To be used on all external links --
function ccsoExternalLinkUrlGet( $pLink,$text=null ) {
	if(is_null( $text )) {
		return '<a href="' . $pLink . '" target="_blank">' . $pLink . '</a>';
	}
	else {
		return '<a href="' . $pLink . '" target="_blank">' . $text . '</a>';
	}
}
// -- END ( external links)

function ccsoButtonHelpHtmlGet( $pId ) {
	$security = ccsoSecurityLevelGet();
	$locale = ccsoLocaleGet();
	return "<input type=\"button\" value=\"View Help\" class=\"popup_button\" id='help_button' data-help-url='" . CCSO_WEB_PAGE_TO_ROOT . "vulnerabilities/view_help.php?id={$pId}&security={$security}&locale={$locale}' )\">";
}


function ccsoButtonSourceHtmlGet( $pId ) {
	$security = ccsoSecurityLevelGet();
	return "<input type=\"button\" value=\"View Source\" class=\"popup_button\" id='source_button' data-source-url='" . CCSO_WEB_PAGE_TO_ROOT . "vulnerabilities/view_source.php?id={$pId}&security={$security}' )\">";
}


// Database Management --

if( $DBMS == 'MySQL' ) {
	$DBMS = htmlspecialchars(strip_tags( $DBMS ));
	$DBMS_errorFunc = 'mysqli_error()';
}
elseif( $DBMS == 'PGSQL' ) {
	$DBMS = htmlspecialchars(strip_tags( $DBMS ));
	$DBMS_errorFunc = 'pg_last_error()';
}
else {
	$DBMS = "No DBMS selected.";
	$DBMS_errorFunc = '';
}

//$DBMS_connError = '
//	<div align="center">
//		<img src="' . CCSO_WEB_PAGE_TO_ROOT . 'ccso/images/logo.png" />
//		<pre>Unable to connect to the database.<br />' . $DBMS_errorFunc . '<br /><br /></pre>
//		Click <a href="' . CCSO_WEB_PAGE_TO_ROOT . 'setup.php">here</a> to setup the database.
//	</div>';

function ccsoDatabaseConnect() {
	global $_CCSO;
	global $DBMS;
	//global $DBMS_connError;
	global $db;
	global $sqlite_db_connection;

	if( $DBMS == 'MySQL' ) {
		if( !@($GLOBALS["___mysqli_ston"] = mysqli_connect( $_CCSO[ 'db_server' ],  $_CCSO[ 'db_user' ],  $_CCSO[ 'db_password' ], "", $_ccso[ 'db_port' ] ))
		|| !@((bool)mysqli_query($GLOBALS["___mysqli_ston"], "USE " . $_CCSO[ 'db_database' ])) ) {
			//die( $DBMS_connError );
			ccsoLogout();
			ccsoMessagePush( 'Unable to connect to the database.<br />' . $DBMS_errorFunc );
			ccsoRedirect( CCSO_WEB_PAGE_TO_ROOT . 'setup.php' );
		}
		// MySQL PDO Prepared Statements (for impossible levels)
		$db = new PDO('mysql:host=' . $_CCSO[ 'db_server' ].';dbname=' . $_CCSO[ 'db_database' ].';port=' . $_CCSO['db_port'] . ';charset=utf8', $_ccso[ 'db_user' ], $_ccso[ 'db_password' ]);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
	elseif( $DBMS == 'PGSQL' ) {
		//$dbconn = pg_connect("host={$_CCSO[ 'db_server' ]} dbname={$_CCSO[ 'db_database' ]} user={$_CCSO[ 'db_user' ]} password={$_ccso[ 'db_password' ])}"
		//or die( $DBMS_connError );
		ccsoMessagePush( 'PostgreSQL is not currently supported.' );
		ccsoPageReload();
	}
	else {
		die ( "Unknown {$DBMS} selected." );
	}

	if ($_CCSO['SQLI_DB'] == SQLITE) {
		$location = CCSO_WEB_PAGE_TO_ROOT . "database/" . $_CCSO['SQLITE_DB'];
		$sqlite_db_connection = new SQLite3($location);
		$sqlite_db_connection->enableExceptions(true);
	#	print "sqlite db setup";
	}
}

// -- END (Database Management)


function ccsoRedirect( $pLocation ) {
	session_commit();
	header( "Location: {$pLocation}" );
	exit;
}

// XSS Stored guestbook function --
function ccsoGuestbook() {
	$query  = "SELECT name, comment FROM guestbook";
	$result = mysqli_query($GLOBALS["___mysqli_ston"],  $query );

	$guestbook = '';

	while( $row = mysqli_fetch_row( $result ) ) {
		if( ccsoSecurityLevelGet() == 'impossible' ) {
			$name    = htmlspecialchars( $row[0] );
			$comment = htmlspecialchars( $row[1] );
		}
		else {
			$name    = $row[0];
			$comment = $row[1];
		}

		$guestbook .= "<div id=\"guestbook_comments\">Name: {$name}<br />" . "Message: {$comment}<br /></div>\n";
	}
	return $guestbook;
}
// -- END (XSS Stored guestbook)


// Token functions --
function checkToken( $user_token, $session_token, $returnURL ) {  # Validate the given (CSRF) token
	if( $user_token !== $session_token || !isset( $session_token ) ) {
		ccsoMessagePush( 'CSRF token is incorrect' );
		ccsoRedirect( $returnURL );
	}
}

function generateSessionToken() {  # Generate a brand new (CSRF) token
	if( isset( $_SESSION[ 'session_token' ] ) ) {
		destroySessionToken();
	}
	$_SESSION[ 'session_token' ] = md5( uniqid() );
}

function destroySessionToken() {  # Destroy any session with the name 'session_token'
	unset( $_SESSION[ 'session_token' ] );
}

function tokenField() {  # Return a field for the (CSRF) token
	return "<input type='hidden' name='user_token' value='{$_SESSION[ 'session_token' ]}' />";
}
// -- END (Token functions)


// Setup Functions --
$PHPUploadPath    = realpath( getcwd() . DIRECTORY_SEPARATOR . CCSO_WEB_PAGE_TO_ROOT . "hackable" . DIRECTORY_SEPARATOR . "uploads" ) . DIRECTORY_SEPARATOR;
$PHPIDSPath       = realpath( getcwd() . DIRECTORY_SEPARATOR . CCSO_WEB_PAGE_TO_ROOT . "external" . DIRECTORY_SEPARATOR . "phpids" . DIRECTORY_SEPARATOR . ccsoPhpIdsVersionGet() . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "IDS" . DIRECTORY_SEPARATOR . "tmp" . DIRECTORY_SEPARATOR . "phpids_log.txt" );
$PHPCONFIGPath       = realpath( getcwd() . DIRECTORY_SEPARATOR . CCSO_WEB_PAGE_TO_ROOT . "config");


$phpDisplayErrors = 'PHP function display_errors: <em>' . ( ini_get( 'display_errors' ) ? 'Enabled</em> <i>(Easy Mode!)</i>' : 'Disabled</em>' );                                                  // Verbose error messages (e.g. full path disclosure)
$phpSafeMode      = 'PHP function safe_mode: <span class="' . ( ini_get( 'safe_mode' ) ? 'failure">Enabled' : 'success">Disabled' ) . '</span>';                                                   // DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0
$phpMagicQuotes   = 'PHP function magic_quotes_gpc: <span class="' . ( ini_get( 'magic_quotes_gpc' ) ? 'failure">Enabled' : 'success">Disabled' ) . '</span>';                                     // DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0
$phpURLInclude    = 'PHP function allow_url_include: <span class="' . ( ini_get( 'allow_url_include' ) ? 'success">Enabled' : 'failure">Disabled' ) . '</span>';                                   // RFI
$phpURLFopen      = 'PHP function allow_url_fopen: <span class="' . ( ini_get( 'allow_url_fopen' ) ? 'success">Enabled' : 'failure">Disabled' ) . '</span>';                                       // RFI
$phpGD            = 'PHP module gd: <span class="' . ( ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) ? 'success">Installed' : 'failure">Missing - Only an issue if you want to play with captchas' ) . '</span>';                    // File Upload
$phpMySQL         = 'PHP module mysql: <span class="' . ( ( extension_loaded( 'mysqli' ) && function_exists( 'mysqli_query' ) ) ? 'success">Installed' : 'failure">Missing' ) . '</span>';                // Core CCSO
$phpPDO           = 'PHP module pdo_mysql: <span class="' . ( extension_loaded( 'pdo_mysql' ) ? 'success">Installed' : 'failure">Missing' ) . '</span>';                // SQLi
$CCSORecaptcha    = 'reCAPTCHA key: <span class="' . ( ( isset( $_CCSO[ 'recaptcha_public_key' ] ) && $_CCSO[ 'recaptcha_public_key' ] != '' ) ? 'success">' . $_ccso[ 'recaptcha_public_key' ] : 'failure">Missing' ) . '</span>';

$CCSOUploadsWrite = '[User: ' . get_current_user() . '] Writable folder ' . $PHPUploadPath . ': <span class="' . ( is_writable( $PHPUploadPath ) ? 'success">Yes' : 'failure">No' ) . '</span>';                                     // File Upload
$bakWritable = '[User: ' . get_current_user() . '] Writable folder ' . $PHPCONFIGPath . ': <span class="' . ( is_writable( $PHPCONFIGPath ) ? 'success">Yes' : 'failure">No' ) . '</span>';   // config.php.bak check                                  // File Upload
$CCSOPHPWrite     = '[User: ' . get_current_user() . '] Writable file ' . $PHPIDSPath . ': <span class="' . ( is_writable( $PHPIDSPath ) ? 'success">Yes' : 'failure">No' ) . '</span>';                                              // PHPIDS

$CCSOOS           = 'Operating system: <em>' . ( strtoupper( substr (PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : '*nix' ) . '</em>';
$SERVER_NAME      = 'Web Server SERVER_NAME: <em>' . $_SERVER[ 'SERVER_NAME' ] . '</em>';                                                                                                          // CSRF

$MYSQL_USER       = 'Database username: <em>' . $_CCSO[ 'db_user' ] . '</em>';
$MYSQL_PASS       = 'Database password: <em>' . ( ($_CCSO[ 'db_password' ] != "" ) ? '******' : '*blank*' ) . '</em>';
$MYSQL_DB         = 'Database database: <em>' . $_CCSO[ 'db_database' ] . '</em>';
$MYSQL_SERVER     = 'Database host: <em>' . $_CCSO[ 'db_server' ] . '</em>';
$MYSQL_PORT       = 'Database port: <em>' . $_CCSO[ 'db_port' ] . '</em>';
// -- END (Setup Functions)

?>
