# THE COMPETITIVE CYBER SECURITY ORGANIZATION DAMN VULNERABLE WEB APPLICATION

The Pennsylvania State University CCSO DVWA is a fork of digininja's Damn Vulnerable Web application \
intended for the use by the Collegiate Penetration Testing Competition Team. It is designed for use \
in a CTF environment to practice basic web exploits.\ 

NOTE: This application was originally created by digininja. While we did make many aesthetic and \
functional changes to it, we are not the original creators of the DVWA. All credit goes to Robin Wood \
and any other creators/contributors to the DVWA.

# Installation notes

1. `git clone https://github.com/nicobove94/CCSO-DVWA.git`
2. `sudo vim /etc/php/<VERSION>/apache2/php.ini`
	> 2a. Note, do not type <VERSION> verbatim, replace <VERSION> with the version number of php you are running \
	> 2b. Check you php version number with php -v, mine is 8.1.2 \
	> 2c. Edit line 861 (or wherever the var is for you) -> `allow_url_fopen = On` \
	> 2d. Edit line 865 (or wherever the var is for you) -> `allow_url_include = On`
3. `sudo apt install php-gd`
4. `sudo mv CCSO-DVWA /var/www/html`
5. `sudo systemctl enable --now apache2`
6. `sudo systemctl enable --now mariadb`
	> 4a. In a web browser in kali navigate to `localhost` \
	> 4b. This should be a default apache page \
7. `cd /var/www/html/CCSO-DVWA; cp config/config.inc.php.dist config/config.inc.php`
8. `sudo su; mysql`
	> 8a. (From mysql CLI) \
			`create database ccso;` \
			`create user ccso@localhost identified by 'p@ssw0rd';` \
			`grant all on ccso.* to ccso@localhost;` \
			`flush privileges;`
9. `cd /var/www/html/CCSO-DVWA; sudo chgrp www-data hackable/uploads`
10. `sudo chgrp www-data /var/www/html/CCSO-DVWA/external/phpids/0.6/lib/IDS/tmp/phpids_log.txt`
11. `sudo chmod g+w hackable/uploads`
12. `sudo chmod g+w /var/www/html/CCSO-DVWA/external/phpids/0.6/lib/IDS/tmp/phpids_log.txt`
`
