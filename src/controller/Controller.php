<?php

namespace src\controller;

use src\model\PostManager;
use src\model\CommentManager;
use src\model\UserManager;
use src\model\ContactManager;
use src\model\ContentManager;
use src\view\View;
use config\Request;
use config\Parameter;
use src\constraint\Validation;
use Exception;

/**
 * Class Controller
 * Generate new controllers 
 */
abstract class Controller
{
	/**
	 * @var PostManager
	 */
	protected $postManager;

	/**
	 * @var CommentManager
	 */
	protected $commentManager;

	/**
	 * @var UserManager
	 */
	protected $userManager;

	/**
	 * @var ContactManager
	 */
	protected $contactManager;

	/**
	 * @var ContentManager
	 */
	protected $contentManager;

	/**
	 * @var View
	 */
	protected $view;

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var Validation
	 */
	protected $validation;

	public function __construct()
	{
		$this->postManager = new PostManager();
		$this->commentManager = new CommentManager();
		$this->userManager = new UserManager();
		$this->contactManager = new ContactManager();
		$this->contentManager = new ContentManager();
		$this->view = new View();
		$this->request = new Request();
		$this->validation = new Validation();
	}
}