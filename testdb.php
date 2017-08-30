<?
require_once('db.php');


$db = DB::getDB();
$r = $db->tableExists('testo');
$ins = "INSERT INTO testo (str) VALUES ('asdf')";
$sel = 'SELECT * FROM testo';

if($r)
{
	print 'Table data:<br>';
	$db->query($ins);
	$r = $db->query($sel);
	print_r($r);
}
else
{
	print 'Create table';
	$db->query('CREATE TABLE testo(
		id INT NOT NULL AUTO_INCREMENT,
		str VARCHAR(30),
		PRIMARY KEY(id))');
	$db->query($ins);
	$r = $db->query($sel);
	print_r($r);
}

?>
