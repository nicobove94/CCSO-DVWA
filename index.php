<?php

define( 'CCSO_WEB_PAGE_TO_ROOT', '' );
require_once CCSO_WEB_PAGE_TO_ROOT . 'ccso/includes/ccsoPage.inc.php';

ccsoPageStartup( array( 'authenticated', 'phpids' ) );

$page = ccsoPageNewGrab();
$page[ 'title' ]   = 'Welcome' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'home';

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>The Competitive Cyber Security Organization Vulnerable Web Application</h1>
	<p>The Competitive Cyber Security Organization's Vulnerable Web Application is a fork of digininja's Damn Vulnerable Web Application (" . ccsoExternalLinkUrlGet('https://github.com/digininja/DVWA', 'DVWA') . "). It is intended for the members of the Collegiate Penetration Testing Competition Team to practice implementing various attacks in a capture-the-flag environment.</p>
	<p>DISCLAIMER: This application was originally created by digininja. While we did make many aesthetic and functional changes to it, <em>we are not the original creators of the DVWA.</em> All credit is to the original creators of the DVWA.</p>
	<hr />
	<br />

	<h2>General Instructions</h2>
	<p>Click on a vulnerability and exploit it to exfiltrate the flag. Try to think outside the box. When you have discovered a flag, input it into the CCSO's CTF platform.</p>
	<hr />
	<br />

  <h1>The following text is from the original creators of the DVWA.</h1>
	<h2>WARNING!</h2>
	<p>Damn Vulnerable Web Application is damn vulnerable! <em>Do not upload it to your hosting provider's public html folder or any Internet facing servers</em>, as they will be compromised. It is recommend using a virtual machine (such as " . ccsoExternalLinkUrlGet( 'https://www.virtualbox.org/','VirtualBox' ) . " or " . ccsoExternalLinkUrlGet( 'https://www.vmware.com/','VMware' ) . "), which is set to NAT networking mode. Inside a guest machine, you can download and install " . ccsoExternalLinkUrlGet( 'https://www.apachefriends.org/','XAMPP' ) . " for the web server and database.</p>
	<br />
	<h3>Disclaimer</h3>
	<p>We do not take responsibility for the way in which any one uses this application (DVWA). We have made the purposes of the application clear and it should not be used maliciously. We have given warnings and taken measures to prevent users from installing DVWA on to live web servers. If your web server is compromised via an installation of DVWA it is not our responsibility it is the responsibility of the person/s who uploaded and installed it.</p>
	<hr />
	<br />

	<h2>More Training Resources</h2>
	<p>DVWA aims to cover the most commonly seen vulnerabilities found in today's web applications. However there are plenty of other issues with web applications. Should you wish to explore any additional attack vectors, or want more difficult challenges, you may wish to look into the following other projects:</p>
	<ul>
		<li>" . ccsoExternalLinkUrlGet( 'https://github.com/webpwnized/mutillidae', 'Mutillidae') . "</li>
		<li>" . ccsoExternalLinkUrlGet( 'https://owasp.org/www-project-broken-web-applications/migrated_content', 'OWASP Broken Web Applications Project
') . "</li>
	</ul>
	<hr />
	<br />
</div>";

ccsoHtmlEcho( $page );

?>
