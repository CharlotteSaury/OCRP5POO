<?php

namespace Src\Model;

use Src\Entity\Post;
use Src\Entity\Content;
use Src\Entity\User;
use Src\Entity\Contact;
use Src\Entity\Answer;
use Src\Entity\Comment;
use Config\Parameter;


/**
 * Class Manager
 * Connexion to database and request managment
 */
abstract class Manager
{
	private $database;

	/**
	 * Database request
	 * @param  string $sql    [SQL request]
	 * @param  array $params [optional mixed type parameter regarding the method]
	 * @return object PDOStatement
	 */
	protected function dbRequest($sql, $params = null)
	{
		if ($params == null) {
			$req = $this->dbConnect()->query($sql);
			return $req;
		}
		$req = $this->dbConnect()->prepare($sql, $params);
		return $req;
	}

	/**
	 * Database connexion
	 * @return object PDO
	 */
	private function dbConnect()
	{
		if ($this->database == null) {
			$this->database = new \PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',DB_USER,DB_PASS);
			$this->database->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		return $this->database;
	}
}

