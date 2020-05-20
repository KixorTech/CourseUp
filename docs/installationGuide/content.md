Installation Guide
--------

### Windows Installation Guide
1. Download the appropriate version of Windows [here](https://www.apachelounge.com/download/).
	
- Ensure you have Microsoft C++ runtime installed. It can be found [here](https://www.microsoft.com/en-us/download/details.aspx?id=48145).
	
2. Extract the Apache24 folder to your base C:\ drive.

3. Open the httpd.conf file located in the conf directory.
- Find the line `#LoadModule rewrite_module modules/mod_rewrite.so` and remove the # at the beginning.
- Change the first two `AllowOverride none` lines to `AllowOverride all`.
	
4. Open a command prompt as an administrator.
- Navigate to the apache directory and then the bin folder.
- Execute the command: `httpd -k install.`
	
5. Open the Services application in Windows.
- Find Apache2.4
- Right click the service and hit start.
	
6. Open your desired web browser and type "localhost" into the search bar.
- When the page loads it should say "It works!"
- If not then something wrong has occurred during the installation.
	
7. Go back and stop the Apache service from the Services page by right clicking and hitting "Stop".

8. Download thread safe php7 for Windows from [here](https://www.php.net/downloads.php).

9. Extract the archive to `C:\php7`.

10. Open the php7 folder.

- Rename `php.ini-developmen`t to `php.ini`

11. Go to `Control Panel > View advanced system settings > Environment Variables`.

- Click the "path" variable under your user.
- Click edit add a new entry for `C:\php7`.
-. Do the same for the system path variable.

12. Open your httpd.conf file (C:\apache24\conf\httpd.conf).

- Change the directory index line to: `DirectoryIndex index.php`

- At the end of the file, add the following lines: 
      ```
      PHPIniDir 'C:/php7'
      AddHandler application/x-httpd-php .php
      LoadModule php7_module 'C:/php7/php7apache2_4.dll'
      ```

- The version numbers may be different for your installation, so make sure they match up.

13. Go back to the Services page and start the Apache service.

14. Paste the cloned CourseUp repository into the Apache24/htdocs folder.

-. Ensure the name of the folder is "CourseUp".

15. Open the CourseUp folder.

- Right click and Run with PowerShell on windows-install.ps

16. Navigate to localhost/index.php

17. Enjoy your CourseUp site!



### Linux Installation Guide

1. Follow the guide [here](https://www.ostechnix.com/install-apache-mysql-php-lamp-stack-on-ubuntu-18-04-lts/), but do not install MySQL
2. Find your Apache httpd.conf file (usually at /etc/apache2/httpd.conf)
-. Find the line `#LoadModule rewrite_module modules/mod_rewrite.so` and remove the # at the beginning.
- Change the first two `AllowOverride none` lines to `AllowOverride all`
3. Clone the CourseUp repo into the Apache web root folder (usually /var/www/html/).
4. In the Apache root folder run the command: `ln -s ./CourseUp/base/* ./`
5. Navigate to the URL you set following the first step (you may have to add /index.php to the end)
6. Enjoy your CourseUp site!



### MacOS Installation Guide

1. Follow the tutorial [here](https://getgrav.org/blog/macos-catalina-apache-multiple-php-versions).
- Do not worry about installing multiple versions of PHP, **just use PHP7**.
2. Edit your Apache httpd.conf with the following changes (usually found at /usr/local/etc/httpd/httpd.conf).
- Find the line `#LoadModule rewrite_module modules/mod_rewrite.so` and remove the # at the beginning.
- Change the first two `AllowOverride none` lines to `AllowOverride all`
3. Navigate to your web directory you set during the tutorial (should be /Users/your_user/Sites).
- Clone the CourseUp repo into this directory.
- Run the command (from the web root directory): `ln -s ./CourseUp/base/* ./`
4. Navigate to the localhost url you set up (you may have to add /index.php to the end).
5. Enjoy your CourseUp site!







