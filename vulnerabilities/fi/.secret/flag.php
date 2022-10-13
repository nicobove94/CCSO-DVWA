<?php

$page[ 'body' ] .= "
<div class=\"body_padded\">
        <h1>Vulnerability: File Inclusion</h1>
        <div class=\"vulnerable_code_area\">
                <h3>Flag</h3>
		<hr />PSU{I_W@NT_2b_!nCLUd3D}</div>

        <h2>More Information</h2>
        <ul>
                <li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Remote_File_Inclusion', 'Wikipedia - File inclusion vulnerability' ) . "</li>
                <li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/stable/4-Web_Application_Security_Testing/07-Input_Validation_Testing/11.1-Testing_for_Local_File_Inclusion', 'WSTG - Local File Inclusion' ) . "</li>
                <li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-project-web-security-testing-guide/stable/4-Web_Application_Security_Testing/07-Input_Validation_Testing/11.2-Testing_for_Remote_File_Inclusion', 'WSTG - Remote File Inclusion' ) . "</li>
        </ul>
</div>\n";

?>
