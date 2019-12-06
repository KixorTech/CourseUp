CourseUp
--------

For Windows installation, clone the dev-windows branch into the htdocs/ directory inside your Apache installation.

First, run *windows-install.ps1* to put everything in the right place.

Next, in your php installation, locate the file *php.ini*. 
Add the following line immediately after the first *[PHP]* (this is probably the very first line):
	extension=php_mbstring.dll

Finally, restart your Apache Server, then check out localhost/index.php on your favorite web browser.