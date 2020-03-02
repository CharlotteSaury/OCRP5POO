<?php

namespace src\controller;

use src\model\PostManager;
use src\model\CommentManager;
use src\model\UserManager;
use src\model\ContactManager;
use src\model\ContentManager;
use src\view\View;
use config\Parameter;
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