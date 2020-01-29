<?php

namespace controller;

require_once('./model/Manager.php');
require_once('./model/PostManager.php');
require_once('./model/CommentManager.php');

use model\CommentManager;
use model\PostManager;

class CommentController

{
	private $_commentManager,
			$_postManager;

	public function __construct()
	{
		$this->_commentManager = new CommentManager();
		$this->_postManager = new PostManager();
	}

}




