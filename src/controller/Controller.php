<?php

namespace Src\Controller;

use Src\Model\PostManager;
use Src\Model\CommentManager;
use Src\Model\UserManager;
use Src\Model\ContactManager;
use Src\Model\ContentManager;
use Src\View\View;
use Src\Config\Request;
use Src\Config\Parameter;
use Src\Constraint\Validation;
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