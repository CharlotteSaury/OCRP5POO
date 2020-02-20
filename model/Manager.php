<?php

namespace model;

include('config.php');

abstract class Manager
{
	private $_database;

	protected function dbRequest($sql, $params = null)
	{
		if ($params == null)
		{
			$req = $this->dbConnect()->query($sql);
			return $req;
		}
		
		$req = $this->dbConnect()->prepare($sql, $params);
		return $req;
	}

	private function dbConnect()
	{
		if ($this->_database == null)
		{
			$this->_database = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',DB_USER,DB_PASS);
			$this->_database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		
		return $this->_database;
	}
}

