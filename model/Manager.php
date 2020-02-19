<?php

namespace model;

include('config.php');

abstract class Manager
{
	private $db;

	protected function dbRequest($sql, $params = null)
	{
		if ($params == null)
		{
			$req = $this->dbConnect()->query($sql);
		}
		else
		{
			$req = $this->dbConnect()->prepare($sql, $params);
		}
		return $req;
	}

	private function dbConnect()
	{
		if ($this->db == null)
		{
			$this->db = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',DB_USER,DB_PASS);
			$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		
		return $this->db;
	}
}

