<?php

define( 'CCSO_WEB_PAGE_TO_ROOT', '' );
require_once CCSO_WEB_PAGE_TO_ROOT . 'ccso/includes/ccsoPage.inc.php';

ccsoPageStartup( array( 'phpids' ) );

$page = ccsoPageNewGrab();
$page[ 'title' ]   = 'About' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'about';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h2>About</h2>
	<p>The Competitive Cyber Security Organization club is a cool club. Hell yeah!/p>
	<p>Pre-August 2020, All material is copyright 2008-2015 RandomStorm & Ryan Dewhurst.</p>
	<p>Ongoing, All material is copyright Robin Wood and probably Ryan Dewhurst.</p>

	<h2>Links</h2>
	<ul>
		<li>Project Home: " . ccsoExternalLinkUrlGet( 'https://github.com/digininja/CCSO' ) . "</li>
		<li>Bug Tracker: " . ccsoExternalLinkUrlGet( 'https://github.com/digininja/CCSO/issues' ) . "</li>
		<li>Wiki: " . ccsoExternalLinkUrlGet( 'https://github.com/digininja/CCSO/wiki' ) . "</li>
	</ul>

	<h2>Credits</h2>
	<ul>
		<li>Brooks Garrett: " . ccsoExternalLinkUrlGet( 'http://brooksgarrett.com/','www.brooksgarrett.com' ) . "</li>
		<li>Craig</li>
		<li>g0tmi1k: " . ccsoExternalLinkUrlGet( 'https://blog.g0tmi1k.com/','g0tmi1k.com' ) . "</li>
		<li>Jamesr: " . ccsoExternalLinkUrlGet( 'https://www.creativenucleus.com/','www.creativenucleus.com' ) . "</li>
		<li>Jason Jones</li>
		<li>RandomStorm</li>
		<li>Ryan Dewhurst: " . ccsoExternalLinkUrlGet( 'https://wpscan.com/','wpscan.com' ) . "</li>
		<li>Shinkurt: " . ccsoExternalLinkUrlGet( 'http://www.paulosyibelo.com/','www.paulosyibelo.com' ) . "</li>
		<li>Tedi Heriyanto: " . ccsoExternalLinkUrlGet( 'http://tedi.heriyanto.net/','tedi.heriyanto.net' ) . "</li>
		<li>Tom Mackenzie</li>
		<li>Robin Wood: " . ccsoExternalLinkUrlGet( 'https://digi.ninja/','digi.ninja' ) . "</li>
		<li>Zhengyang Song: " . ccsoExternalLinkUrlGet( 'https://github.com/songzy12/','songzy12' ) . "</li>
	</ul>
	<ul>
		<li>PHPIDS - Copyright (c) 2007 " . ccsoExternalLinkUrlGet( 'http://github.com/PHPIDS/PHPIDS', 'PHPIDS group' ) . "</li>
	</ul>

	<h2>License</h2>
	<p>Damn Vulnerable Web Application (CCSO) is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.</p>
	<p>The PHPIDS library is included, in good faith, with this CCSO distribution. The operation of PHPIDS is provided without support from the CCSO team. It is licensed under <a href=\"" . CCSO_WEB_PAGE_TO_ROOT . "instructions.php?doc=PHPIDS-license\">separate terms</a> to the CCSO code.</p>

	<h2>Development</h2>
	<p>Everyone is welcome to contribute and help make CCSO as successful as it can be. All contributors can have their name and link (if they wish) placed in the credits section. To contribute pick an Issue from the Project Home to work on or submit a patch to the Issues list.</p>
</div>\n";

ccsoHtmlEcho( $page );

exit;

?>
