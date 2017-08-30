<?
require_once('common.php');

class Table
{
	protected $name;
	protected $fields;
	protected $types;
	protected $keys;
	protected $bindings;

	protected function create()
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

	protected function clearNulls($row)
	{
		reset($row);
		$newRow = Array();
		//while($v = current($row))
		for($i=0; $i<count($row); $i++)
		{
			$v = current($row);
			$k = key($row);
			if($v != NULL)
				$newRow[$k] = $v;
			next($row);
		}
		return $newRow;
	}

	protected function getWhereClause($row, $boolean=NULL)
	{
		$row = $this->clearNulls($row);
		$fieldCount = count($row);
		$boolCount = is_null($boolean) ? 0 : count($boolean);

		$conditionBoolCountMatch = ($boolCount+1 == $fieldCount);
		assert($conditionBoolCountMatch);

		$q = "WHERE ";

		//$elementId = 0;
		for(reset($row),$elementId=0; $v = current($row); next($row),$elementId++)
		//while($v = current($row))
		{
			$k = key($row);
			$v = addslashes($v);
			$q .= "$k='$v'";
			if($elementId < $fieldCount-1) $q .= " $boolean[$elementId] ";
			//$elementId++;
			//next($row);
		}

		return $q;
	}

	/*** deprecated ***/
	protected function getWhere($r, $boolean)
	{
		$fieldCount = count($r);
		$q = "WHERE ";

		for($i = 0; $i < $fieldCount; $i++) {
			$q .= $fields[$i] .'='. $r[$fields[$i]];
			if($i < $fieldCount-1) $q .= " $boolean[$i] ";
		}

		return $q;
	}

	protected function getEmptyRow()
	{
		$fieldCount = count($this->fields);

		$r = Array();
		for($i = 0; $i < $fieldCount-1; $i++)
			$r[$this->fields[$i]] = NULL;
		return $r;
	}

	protected function getInsertQuery($row)
	{
		$row = $this->clearNulls($row);
		$fieldCount = count($row);

		$q = "INSERT INTO $this->name (";

		for(reset($row),$elementId=0; $v = current($row); next($row),$elementId++)
		{
			$k = key($row);
			$q .= "$k";
			if($elementId < $fieldCount-1) $q .= ", ";
		}

		$q .= ') VALUES (';

		for(reset($row),$elementId=0; $v = current($row); next($row),$elementId++)
		{
			$v = addslashes($v);
			$q .= "'$v'";
			if($elementId < $fieldCount-1) $q .= ", ";
		}

		$q .= ')';
		return $q;
	}

	/*** deprecated ***/
	protected function insert($r)
	{
		$fieldCount = count($r);
		$fields = array_keys($r);

		$q = "INSERT INTO $this->name (";

		for($i = 0; $i < $fieldCount; $i++) {
			$q .= $fields[$i];
			if($i < $fieldCount-1) $q .=', ';
		}

		$q .= ') VALUES (';

		for($i = 0; $i < $fieldCount; $i++) {
			$q .= "'" . $r[$fields[$i]] . "'";
			if($i < $fieldCount-1) $q .=', ';
		}

		$q .= ')';
		return $q;
	}

	/*** deprecated ***/
	protected function update($r)
	{
		$fieldCount = count($r);

		$db = DB::getDB();
		$q = "UPDATE $this->name SET ";
		for($i = 0; $i < $fieldCount; $i++) {
			$q .= $fields[$i] .'='. $r[$fields[$i]] .' ';
			if($i < $fieldCount-1) $q .=', ';
		}
		$q .= $this->getWhere();
		return $q;
	}

	/*** deprecated ***/
	protected function select()
	{
		$q = "SELECT * FROM $this->name ";
		$q .= $this->getWhere();
		return $q;
	}

	public function test()
	{
		//print "<br>\ngetData "; print_r($this->getData());
		print "<br>\ncreate " . $this->create();
		print "<br>\nwhere " . $this->getWhere();
		print "<br>\ninsert " . $this->insert();
		print "<br>\nupdate " . $this->update();
		print "<br>\nselect " . $this->select();
	}

}

class TESTTable extends Table
{
	public function __construct()
	{
		$this->name = 'TEXT';
		$this->fields = array('id', 'username', 'url', 'pos', 'value', 'timestamp');
		$this->types = array('INT NOT NULL AUTO_INCREMENT UNSIGNED', 'VARCHAR(30)', 'VARCHAR(400)', 'VARCHAR(30)', 'TEXT', 'DATETIME');
		$this->keys = array('username', 'url', 'name');
		print $this->create();
		$r = $this->getEmptyRow();
		$r['username'] = 'test';
		$r['url'] = 'http://example';
		print $this->getWhereClause($r, Array('AND'));
		print $this->getInsertQuery($r);
	}
}

$b = new TESTTable();
//print_r($b);
//$b->test();


?>

