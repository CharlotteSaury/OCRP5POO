<?php

namespace src\controller;

use src\controller\Controller;

class UserController extends Controller

{
	public function inscriptionView($message = null) 
	{
		return $this->view->render('frontend', 'inscriptionView', ['message' => $message]);
	}

	public function connexionView($message = null) 
	{
		return $this->view->render('frontend', 'connexionView', ['message' => $message]);
	}

	public function newUser($pseudo, $pass, $email)
	{
		$activation_code = $this->userManager->addUser($pseudo, $pass, $email);
		return $activation_code;
	}

	public function checkPseudo($pseudo, $userId = null)
	{
		$pseudoExists = $this->userManager->pseudoExists($pseudo, $userId);
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
		$emailExists = $this->userManager->emailExists($email, $userId);
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
		$user = $this->userManager->getUser($userId = null, $email);

		if ($user->actCode() != null)
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
		$user = $this->userManager->getUser($userId = null, $email);

		if ($user->actCode() != $key)
		{
			$message = 'La clé d\'activation n\'est pas bonne, veuillez retourner sur votre mail d\'activation.';
		}
		else
		{
			$this->userManager->userActivation($email);
			$message = 'Votre compte est maintenant activé, vous pouvez vous connecter ! ';
		}
		return $message;
	}

	public function getPassword($email)
	{
		$user = $this->userManager->getUser($userId = null, $email);
		return $user_pass = $user->password();
	}

	public function newUserSession($email)
	{
		$user = $this->userManager->getUser($userId = null, $email);

        $_SESSION['id'] = $user->id();
        $_SESSION['pseudo'] = $user->pseudo();
        $_SESSION['role'] = $user->userRoleId();
        $_SESSION['avatar'] = $user->avatar();
        $_SESSION['email'] = $email;
	}

	public function isAdmin($userId)
	{
		$user = $this->userManager->getUser($userId);
		if ($user->userRoleId() == 1)
		{
			return true;
		}
		return false;
	}

	public function isSuperAdmin($userId)
	{
		$user = $this->userManager->getUser($userId);
		if ($user->userRoleId() == 3)
		{
			return true;
		}
		return false;
	}

	public function forgotPassView($message = null)
	{
		return $this->view->render('frontend', 'forgotPassView', ['message' => $message]);
	}

	public function newPassCode($email)
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$random_code = substr(str_shuffle($permitted_chars), 0, 10);
		$reinit_code = password_hash($random_code, PASSWORD_DEFAULT);
		$this->userManager->newPassCode($email, $reinit_code);
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
		$user = $this->userManager->getUser($userId = null, $email);
		return $reinit_code = $user->reinitCode();
	}

	public function newPassView($email, $message = null, $status)
	{
		return $this->view->render('frontend', 'newPassView', ['message' => $message]);
	}

	public function newUserPass($email, $newPass)
	{
		$this->userManager->newUserPass($email, $newPass);
	}

}
