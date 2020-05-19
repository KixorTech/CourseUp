<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

function printHWAuthorBlanks()
{
	print
"<style>
.entrySpace { padding-top: 2em; display: inline-block; border-bottom: 1px solid black; }
.nameSpace { width:20em; }
.boxSpace { width:5em; }
.dateSpace { width:8em; }
</style>

<p>
Name: <span class=\"nameSpace entrySpace\"></span>
Box: <span class=\"boxSpace entrySpace\"></span>
Date: <span class=\"dateSpace entrySpace\"></span>
<p>
";
}

function printVspace($size)
{ print '<div style="height: '.$size.'em;"></div>'; }

function printHspace($size)
{ print '<div style="width: '.$size.'em;"></div>'; }

function getPost($k)
{
	if( isset($_POST[$k]) )
		return $_POST[$k];
	return '';
}

function apiPrint()
{
	$a = func_get_args();
	$c = count($a);

	if($c < 3)
		return;

	$s = '<code>';
	$s .= $a[0];
	$s .= '(';
	for($i=1; $i<$c; $i+=2) {
		$s .= $a[$i];
		if($i < $c-3)
			$s .= ', ';
	}
	$s .= ')';
	$s .= "</code>";

	$s .= "<ul>\n";
	for($i=1; $i<$c; $i+=2) {
		$s .= '<li>';
		$s .= $a[$i];
		$s .= " : ";
		$s .= $a[$i+1];
		$s .= "</li>\n";
	}
	$s .= "</ul>\n";

	print $s;
}


function useMessage()
{
	print '<link rel="stylesheet" type="text/css" href="' .getWebRoot(). '/include/message.css" />';
	print '<script language="javascript" src="' .getWebRoot(). '/include/message.js"></script>';
	print '<script language="javascript" src="' .getWebRoot(). '/include/drag.js"></script>';
}

function getClientIP()
{
	$ip = $_SERVER['REMOTE_ADDR'];

	$usingProxy = isset( $_SERVER['HTTP_X_FORWARDED_FOR']);
	if($usingProxy)
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

	return $ip;
}

function requireLatex()
{
	embedLatex(); 
}

function embedLatex()
{
	//<!--script src="../../ASCIIMathML.js"></script-->
	print '<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML"> MathJax.Hub.Config({jax: ["input/TeX","output/HTML-CSS"],displayAlign: "left"}); </script>';
}

function printPaths()
{
	print "file ". __FILE__;
	print '<br>';

	$s = $_SERVER['SCRIPT_FILENAME'];
	print "script file ".$s;
	print '<br>';

	$s = $_SERVER['SCRIPT_NAME'];
	print "script ".$s;
	print '<br>';

	$e = $_SERVER['PHP_SELF'];
	print "self ".$e;
	print '<br>';

	$u = $_SERVER['REQUEST_URI'];
	print "request uri ".$u;
	print '<br>';
}

function printHosts()
{
	print 'gethostname '.gethostname(); 
	print '<br>';
	print 'getphpuname '.php_uname('n');
	print '<br>';
	print 'server_name '.$_SERVER['SERVER_NAME'];
}

function getHost()
{
	try 
	{
		return $_SERVER['SERVER_NAME'];
	} 
	catch (Exception $e)
	{
		return '';
	}
}

function getURI()
{
	$u = $_SERVER['REQUEST_URI'];
	return $u;
}

function getWebRoot()
{
	$rootPath = dirname($_SERVER['PHP_SELF']);

	//remove trailing slash
	// $hasTrailingSlash = substr($rootPath, -1) == '/';
	// if($hasTrailingSlash)
	$rootPath = rtrim($rootPath, '/');

	$rootPath = trim($rootPath, '\\'); // this should work on any OS; only removes if the beginning is "\"
	return $rootPath;
}

function getWebRoot_noRootResourceHandling_deprecated()
{
	$thisSysPath = dirname(__FILE__);
	$scriptSysPath = realpath($_SERVER['SCRIPT_FILENAME']);
	$scriptWebPath = $_SERVER['PHP_SELF'];
	$c = substr_count($scriptSysPath, '/');
	#print '<table border><tr><td>';
	#print 'file: <td>' .__FILE__;
	#print '<tr><td>';
	#print 'script: <td>' .$_SERVER['SCRIPT_FILENAME'];
	#print '<tr><td>';
	#print 'realscript: <td>' .realpath($_SERVER['SCRIPT_FILENAME']);
	#print '<tr><td>';
	#print 'self: <td>' .$_SERVER['PHP_SELF'];
	#print '</table>';

	//removes the dir that contains the support code
	$thisSysPath = dirname($thisSysPath);

	for($i=0; $i<$c; $i++)
	{
		$baseSys = basename($scriptSysPath);
		$baseWeb = basename($scriptWebPath);
		#print 'base sys/web: '.$baseSys .' '.$baseWeb .'<br>';
		assert($baseSys == $baseWeb);

		$scriptSysPath = dirname($scriptSysPath);
		$scriptWebPath = dirname($scriptWebPath);
		#print 'sys ' .$scriptSysPath.'<br>web '.$scriptWebPath.'<br><br>';

		if($scriptSysPath == $thisSysPath)
			return $scriptWebPath;
	}

	//add a trailing slash
	$hasTrailingSlash = substr($scriptWebPath, -1) != '/';
	if(!$hasTrailingSlash)
		$scriptWebPath = $scriptWebPath . '/';

	return $scriptWebPath;
}

function getFileRoot()
{
	$f = dirname(__FILE__);
	return $f;
}

function getRelativeDocRoot()
{
	$uriPath = $_SERVER['REQUEST_URI'];
	$fileInURI = substr($uriPath, -1) != '/';
	if($fileInURI) $uriPath = dirname($uriPath);
	$uriParts = explode('/', $uriPath);

	$selfPath = dirname($_SERVER['PHP_SELF']);
	$selfParts = explode('/', $selfPath);

	$pos = 0;
	while(
		$pos < count($selfParts) &&
		$pos < count($uriParts) &&
		$selfParts[$pos] == $uriParts[$pos]
		)
		$pos++;

	$docPath = '';
	for( ; $pos < count($uriParts); $pos++)
		$docPath = $docPath . '/' . $uriParts[$pos];

	$docPath = ltrim($docPath, '/');

	return $docPath;
}

function printRoot()
{ printWebRoot(); }

function printWebRoot()
{
	$r = getWebRoot();
	print $r;
}

function checkOnline()
{
	$path = getFileRoot();
	return file_exists($path.'/online');
}


?>

