<?php

namespace controller;

require_once './model/UserManager.php';

use model\UserManager;
use Exception;

class UserController

{
	private $_userManager;

	public function __construct()
	{
		$this->_userManager = new UserManager();
	}

	public function inscriptionView($message = null) 
	{
		require './view/frontend/inscriptionView.php';
	}

	public function connexionView($message = null) 
	{
		require './view/frontend/connexionView.php';
	}

	public function newUser($pseudo, $pass, $email)
	{
		$activation_code = $this->_userManager->addUser($pseudo, $pass, $email);
		var_dump($activation_code);
		return $activation_code;
	}

	public function checkPseudo($pseudo, $userId = null)
	{
		$pseudoExists = $this->_userManager->pseudoExists($pseudo, $userId);
		if ($pseudoExists == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function checkEmail($email, $userId = null)
	{
		$emailExists = $this->_userManager->emailExists($email, $userId);
		if ($emailExists == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}

	public function sendEmailActivation($email, $pseudo, $activation_code)
	{
		$subject = "Bienvenue sur mon blog";
		$headers = "From: " . BLOG_AUTHOR . "\r\n";
		$message = "Bonjour " . $pseudo . ", bienvenue sur mon blog !\r\n
					Pour activer votre compte, veuillez cliquer sur le lien ci-dessous
					ou copier/coller dans votre navigateur Internet.\r\n
					http://blogphp.charlottesaury.fr/index.php?action=activation&email=" . $email . "&key=" . $activation_code . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$message = wordwrap($message, 70, "\r\n");
		mail($email, $subject, $message, $headers);
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

	public function getPassword($email)
	{
		return $user_pass = $this->_userManager->getUserPass($email);
	}

	public function newUserSession($email)
	{
		$sessionInfos = $this->_userManager->getSessionInfos($email);
        $_SESSION['id'] = $sessionInfos[0]['userId'];
        $_SESSION['pseudo'] = $sessionInfos[0]['pseudo'];
        $_SESSION['role'] = $sessionInfos[0]['role'];
        $_SESSION['avatar'] = $sessionInfos[0]['avatar'];
        $_SESSION['email'] = $email;
	}

	public function isAdmin($userId)
	{
		$role = $this->_userManager->getUserRole($userId);
		if ($role == 1)
		{
			return true;
		}
		return false;
	}

	public function isSuperAdmin($userId)
	{
		$role = $this->_userManager->getUserRole($userId);
		if ($role == 3)
		{
			return true;
		}
		return false;
	}

	public function forgotPassView($message = null)
	{
		require './view/frontend/forgotPassView.php';
	}

	public function newPassCode($email)
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$random_code = substr(str_shuffle($permitted_chars), 0, 10);
		$reinit_code = password_hash($random_code, PASSWORD_DEFAULT);
		$this->_userManager->newPassCode($email, $reinit_code);
		return $reinit_code;
	}

	public function forgotPassMail($email, $reinit_code)
	{
		$subject = "Réinitialisation mot de passe";
		$headers = "From: " . BLOG_AUTHOR . "\r\n";
		$content = "Bonjour, \r\n
					Pour réinitialiser le mot de passe du compte " . $email . ", veuillez cliquer sur le lien ci-dessous
					ou copier/coller dans votre navigateur Internet.\r\n
					http://www.blogphp.charlottesaury.fr/index.php?action=newPassView&email=" . $email . "&key=" . $reinit_code . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$content = wordwrap($content, 70, "\r\n");
		mail($email, $subject, $content, $headers);
	}

	public function getNewPassCode($email)
	{
		return $reinit_code = $this->_userManager->getNewPassCode($email);
	}

	public function newPassView($email, $message = null, $status)
	{
		require './view/frontend/newPassView.php';
	}

	public function newUserPass($email, $newPass)
	{
		$this->_userManager->newUserPass($email, $newPass);
	}

}
