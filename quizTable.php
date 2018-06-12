<?
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

require_once('common.php');
require_once('table.php');

class QuizTable extends Table
{
	public function __construct()
	{
		$this->name = 'quiz';
		$this->fields = array('id', 'username', 'url', 'name', 'value', 'timestamp');
		$this->types = array('INT NOT NULL AUTO_INCREMENT UNSIGNED', 'VARCHAR(30)', 'VARCHAR(400)', 'VARCHAR(30)', 'TEXT', 'DATETIME');
		$this->keys = array('username', 'url', 'name');
	}

	protected function getWhere()
	{
		global $session;
		$r = Array();
		$r['url']	= getURI();
		$r['username'] = ' ';
		//if($session->isValid())
		//	$r['username'] = $session->getUsername();
		$r['name'] = getPost('name');
		$q = parent::getWhere($r, Array('AND', 'AND'));

		return $q;
	}

	private function getData()
	{
		$r = $this->getEmptyRow();
		global $session;
		$r['username'] = ' ';
		//if($session->isValid())
		//	$r['username'] = $session->getUsername();
		//$r['url'] = getURI();
		$r['name'] = getPost('name');
		$r['value'] = getPost('value');
		$r['timestamp'] = time();

		return $r;
	}

	protected function insert()
	{
		$r = $this->getData();
		$q = parent::insert();
		return $q;
	}

	protected function update()
	{
		global $session;
		$r = Array();
		$r['username'] = ' ';
		if($session->isValid())
			$r['username'] = $session->getUsername();
		$r['url'] = getURI();
		$r['name'] = getPost('name');
		$r['value'] = getPost('value');
		$r['timestamp'] = time();

		$q = parent::update($r);
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

$b = new QuizTable();
print_r($b);
$b->test();


?>

