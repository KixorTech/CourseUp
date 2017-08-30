<?
include '../CourseUp/PDExtension.php';


$v = $argv[1];
$f = file_get_contents($v);
$p = PDExtension::instance()->text($f); 
echo $p;

?>

