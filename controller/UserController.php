<?php

namespace controller;

require_once('./model/Manager.php');
require_once('./model/PostManager.php');
require_once('./model/CommentManager.php');
require_once('./model/UserManager.php');

use model\PostManager;
use model\CommentManager;
use model\UserManager;
use Exception;

class UserController

{

	private $_postManager;
	private $_commentManager;
	private $_userManager;

	public function __construct()
	{
		$this->_postManager = new PostManager();
		$this->_commentManager = new CommentManager();
		$this->_userManager = new UserManager();
	}

	public function inscriptionView($message = null) 
	{
		require('./view/frontend/inscriptionView.php');
	}

	public function connexionView($message = null) 
	{
		require('./view/frontend/connexionView.php');
	}

	public function newUser($pseudo, $pass, $email)
	{
		$this->_userManager->addUser($pseudo, $pass, $email);
	}

	public function checkPseudo($pseudo)
	{
		$pseudoExists = $this->_userManager->pseudoExists($pseudo);
		if ($pseudoExists == 1)
		{
			$message = "Ce pseudo n'est pas disponible !";
			$this->inscriptionView($message);
		}
		
	}

	public function checkEmail($email)
	{
		$emailExists = $this->_userManager->emailExists($email);
		if ($emailExists == 1)
		{
			$message = "Cet email est déjà associé à un compte. Essayez de vous connecter !";
			$this->inscriptionView($message);
		}
		
	}

}
