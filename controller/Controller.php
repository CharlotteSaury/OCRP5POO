<?php

namespace controller;

require_once './model/Manager.php';
require_once './model/PostManager.php';
require_once './model/CommentManager.php';
require_once './model/UserManager.php';
require_once './model/ContactManager.php';
require_once './model/ContentManager.php';
require_once './view/View.php';

use model\PostManager;
use model\CommentManager;
use model\UserManager;
use model\ContactManager;
use model\ContentManager;
use view\View;
use Exception;

abstract class Controller
{
	protected $postManager,
			$commentManager,
			$userManager,
			$contactManager,
			$contentManager,
			$view;

	public function __construct()
	{
		$this->postManager = new PostManager();
		$this->commentManager = new CommentManager();
		$this->userManager = new UserManager();
		$this->contactManager = new ContactManager();
		$this->contentManager = new ContentManager();
		$this->view = new View();
	}
}