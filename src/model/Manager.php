<?php

namespace src\model;

require_once 'src/entity/Post.php';
require_once 'src/entity/Content.php';
require_once 'src/entity/User.php';
require_once 'src/entity/Contact.php';
require_once 'src/entity/Answer.php';
require_once 'src/entity/Comment.php';

use src\entity\Post;
use src\entity\Content;
use src\entity\User;
use src\entity\Contact;
use src\entity\Answer;
use src\entity\Comment;


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

