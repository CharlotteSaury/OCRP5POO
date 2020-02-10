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
		$activation_code = $this->_userManager->addUser($pseudo, $pass, $email);
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

	public function sendEmailActivation($email, $pseudo, $activation_code)
	{
		$subject = "Bienvenue sur mon blog";
		//$headers = "From : saury.charlotte@wanadoo.fr";
		$message = "Bonjour " . $pseudo . ", bienvenue sur mon blog !\r\n
					Pour activer votre compte, veuillez cliquer sur le lien ci-dessous
					ou copier/coller dans votre navigateur Internet.\r\n
					http://localhost/OCR-P5-Blog-POO/index.php?action=activation&email=" . $email . "&key=" . $activation_code . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$message = wordwrap($message, 70, "\r\n");
		mail($email, $subject, $message/*, $headers*/);
	}

	public function userActivated($email)
	{
		$activation_code = $this->_userManager->getUserCode($email);
		if ($activation_code != null)
		{
			return false;
		}
		else
		{
			return true;
		}

	}

	public function userActivation($email, $key)
	{
		$activation_code = $this->_userManager->getUserCode($email);
		if ($activation_code != $key)
		{
			$message = 'La clé d\'activation n\'est pas bonne, veuillez retourner sur votre mail d\'activation.';
		}
		else
		{
			$this->_userManager->userActivation($email);
			$message = 'Votre compte est maintenant activé, vous pouvez vous connecter ! ';
		}
		return $message;
	}

}
