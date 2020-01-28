<?php

namespace model;

abstract class Manager
{
	private $db;

	const DB_HOST = 'mysql:host=localhost;dbname=phpblogp5_v2;charset=utf8';
	const DB_USER = 'root';
	const DB_PASS = '';

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
			$this->db = new \PDO(self::DB_HOST, self::DB_USER, self::DB_PASS);
			$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		
		return $this->db;
	}
}

