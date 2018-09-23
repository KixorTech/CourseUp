<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/


require_once('common.php');

class Table
{
	private $name;
	private $fields;
	private $types;
	private $keys;
	private $bindings;

	public function __construct()
	{
		$this->name = 'quiz';
		$this->fields = array('id', 'username', 'url', 'name', 'value', 'timestamp');
		$this->types = array('INT NOT NULL AUTO_INCREMENT UNSIGNED', 'VARCHAR(30)', 'VARCHAR(400)', 'VARCHAR(30)', 'TEXT', 'DATETIME');
		$this->keys = array('username', 'url', 'name');
	}

	private function create()
	{
		$fieldCount = count($this->fields);

		$q = "CREATE TABLE $this->name(";
		for($i = 0; $i < $fieldCount; $i++)
			$q .= $this->fields[$i] . ' ' . $this->types[$i] . ', ';
		$q .= 'PRIMARY KEY(id) )';

		return $q;

		$db = DB::getDB();
		if( !$db->tableExists($this->name) )
			$db->query($create);
	}

	private function getWhere()
	{
		global $session;
		$url = getURI();
		$username = ' ';
		if($session->isValid())
			$username = $session->getUsername();
		$name = getPost('name');
		$q = "WHERE url='$url' AND username='$username' AND name='$name'";

		return $q;
	}

	private function getData()
	{
		$r = $this->getEmptyRow();
		global $session;
		$r['username'] = ' ';
		if($session->isValid())
			$r['username'] = $session->getUsername();
		$r['url'] = getURI();
		$r['name'] = getPost('name');
		$r['value'] = getPost('value');
		$r['timestamp'] = time();

		return $r;
	}

	private function getEmptyRow()
	{
		$fieldCount = count($this->fields);

		$r = Array();
		for($i = 0; $i < $fieldCount-1; $i++)
			//print $this->fields[$i];
			$r[$this->fields[$i]] = '';
		return $r;
	}

	private function insert()
	{
		$r = $this->getData();
		$fieldCount = count($r);
		$fields = array_keys($r);

		$q = "INSERT INTO $this->name (";

		for($i = 0; $i < $fieldCount-1; $i++)
			$q .= $fields[$i] . ', ';
		$q .= $this->fields[ $fieldCount-1 ];

		$q .= ') VALUES (';

		for($i = 0; $i < $fieldCount-1; $i++) {
			$q .= "'" . $r[$feilds[$i]] . "'";
			if($i < $fieldCount-1) $q .=', ';
		}
		$q .= $r[ $fields[$fieldCount-1] ];

		$q .= ')';
		return $q;
	}

	private function update()
	{
		global $session;
		$username = ' ';
		if($session->isValid())
			$username = $session->getUsername();
		$url = getURI();
		$name = getPost('name');
		$value = getPost('value');
		$timestamp = time();

		$fieldCount = count($this->fields);

		$db = DB::getDB();
		$q = "UPDATE $this->name SET ";
		$q .= "value='$value', timestamp='$timestamp' ";
		$q .= $this->getWhere();
		return $q;
	}

	private function select()
	{
		$q = "SELECT * FROM $this->name ";
		$q .= $this->getWhere();
		return $q;
	}

	public function test()
	{
		print "<br>\ngetData "; print_r($this->getData());
		print "<br>\ncreate " . $this->create();
		print "<br>\nwhere " . $this->getWhere();
		print "<br>\ninsert " . $this->insert();
		print "<br>\nupdate " . $this->update();
		print "<br>\nselect " . $this->select();
	}

}

$b = new Table();
print_r($b);
$b->test();


?>

