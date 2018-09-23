<?php
/*
This file is part of the CourseUp project.
http://courseup.org

(c) Micah Taylor
micah@kixortech.com

See http://courseup.org for license information.
*/

require_once('common.php');
require_once('fSQL.php');

class DB
{
	private $connection = NULL;
	private static $activeDBs = array();

	private function __construct()
	{
		$path = getFileRoot().'/store';
		$fsql = new fSQLEnvironment;
		$fsql->define_db("store", $path)
			or die($fsql->error());
		$fsql->select_db("store")
			or die($fsql->error());
		$this->connection = $fsql;
	}

	public static function getDB($dbname='store')
	{
		if( isset( DB::$activeDBs[$dbname] ) )
			return DB::$activeDBs[$dbname];

		DB::$activeDBs[$dbname] = new DB($dbname);
		return DB::$activeDBs[$dbname];
	}

	public function getTable($select, $where='', $limit='', $sort='')
	{
		$results = @$this->connection->query("$select $where $limit $sort")
			or die($this->connection->error());

		if( !$results )
			return array();

		$table = array();
		while ($row = mysql_fetch_array( $results ))
			$table[] = $row;

		return $table;
	}

	public function query($q)
	{
		$results = $this->connection->query($q)
			or die($this->connection->error());

		if( !$results )
			return array();

		$table = array();
		while($row = $this->connection->fetch_array($results))
			$table[] = $row;
		return $table;
	}

	public function escape($s)
	{
		if($this->connection == NULL)
			die('<!-- DB formatting down -->');
		$s = $this->connection->escape($s);
		return $s;
	}

	public function unescape($s)
	{
		//hack
		return str_replace('\"', '"', $s);
	}

	public function tableExists($s)
	{
		$q = "select * from $s";
		$r = $this->connection->query($q);
		$hasCols = $this->connection->num_fields($r) > 0;
		 		return $hasCols;
	}

	public function tableExists_show($s)
	{
		$q = "show tables";
		$r = $this->connection->query($q)
			or die($this->connection->error());

		while($row = $this->connection->fetch_array($r))
		{
			if($row['name'] == $s)
				return true;
		}
		return false;
	}

	public function __destruct()
	{
		//if($this->connection != NULL)
			//mysql_close( $this->connection );
	}
}

?>
