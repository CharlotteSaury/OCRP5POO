<?php

namespace config;

use config\Parameter;
use config\Session;

class Request
{
	private $get,
			$post,
			$session,
			$file;

	public function __construct()
	{
		$this->get = new Parameter($_GET);
		$this->post = new Parameter($_POST);
		$this->session = new Session($_SESSION);
		$this->file = new File($_FILES);
	}

	public function getGet()
	{
		return $this->get;
	}

	public function getPost()
	{
		return $this->post;
	}

	public function getSession()
	{
		return $this->session;
	}

	public function getFile()
	{
		return $this->file;
	}

}