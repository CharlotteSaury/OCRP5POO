<?php

namespace Src\Config;

use Src\Config\Parameter;
use Src\Config\Session;

/**
 * Class Request
 * Manage request through global variables ($_POST, $_GET, $_SESSION, $_FILES)
 */
class Request
{
	private $get;
	private $post;
	private $session;
	private $file;

	public function __construct()
	{
		$this->get = new Parameter($_GET);
		$this->post = new Parameter($_POST);
		$this->session = new Session($_SESSION);
		$this->file = new File($_FILES);
	}

	/**
	 * @return Parameter
	 */
	public function getGet()
	{
		return $this->get;
	}

	/**
	 * @return Parameter
	 */
	public function getPost()
	{
		return $this->post;
	}

	/**
	 * @return Session
	 */
	public function getSession()
	{
		return $this->session;
	}

	/**
	 * @return File  
	 */
	public function getFile()
	{
		return $this->file;
	}

}