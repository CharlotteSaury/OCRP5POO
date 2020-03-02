<?php

namespace src\controller;

use src\controller\Controller;
use config\Request;
use config\Parameter;
use config\Session;

class UserController extends Controller

{
	public function isValid($userId)
	{
		$users = $this->userManager->getUsers();
		foreach ($users as $user) 
		{
			if ($user->id() == $userId)
			{
				return true;
			}
		}
		return false;
	}

	public function adminAccess()
	{
		if ($this->request->getSession()->get('id'))
		{
			$userId = $this->request->getSession()->get('id');

			if ($this->isAdmin($userId) || $this->isSuperAdmin($userId))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		return false;
	}

	public function inscriptionView($message = null) 
	{
		return $this->view->render('frontend', 'inscriptionView', ['message' => $message,
			'session' => $this->request->getSession()]);
	}

	public function connexionView($message = null) 
	{
		return $this->view->render('frontend', 'connexionView', ['message' => $message,
			'session' => $this->request->getSession()]);
	}

	public function newUser(Parameter $post)
	{
		$activation_code = $this->userManager->addUser($post);
		return $activation_code;
	}

	public function checkPseudo($pseudo, $userId = null)
	{
		return $pseudoExists = ($this->userManager->pseudoExists($pseudo, $userId) == 1) ? true : false;
	}

	public function checkEmail($email, $userId = null)
	{
		return $emailExists = ($this->userManager->emailExists($email, $userId) == 1) ? true : false;
	}

	public function inscription(Parameter $post)
	{
		if (strlen($post->get('pseudo') < 25))
		{
			if (filter_var($post->get('email'), FILTER_VALIDATE_EMAIL))
			{
				if ($post->get('pass1') == $post->get('pass2'))
				{
					$post->set('pass', password_hash($post->get('pass1'), PASSWORD_DEFAULT));
					var_dump($post);

					if (($this->checkEmail($post->get('email')) || ($this->checkPseudo($post->get('pseudo')))))
					{
						if ($this->checkEmail($post->get('email')))
						{
							$message = 'Cet email est déjà associé à un compte. Essayez de vous <a href="index.php?action=connexionView">connecter</a> !';
						}
						else
						{
							$message = "Ce pseudo n'est pas disponible. Merci d'en choisir un nouveau.";
						}								
					}
					else
					{
						$activation_code = $this->newUser($post);
						$this->sendEmailActivation($post, $activation_code);
						$message = 'Merci pour votre inscription ! Un email de confirmation vous a été envoyé afin de confirmer votre adresse email. Merci de vous reporter à cet email pour activer votre compte ! ';
					}
				}
				else
				{
					$message = 'Les mots de passe saisis sont différents ! ';
				}
			}
			else
			{
				$message = 'L\'adresse email n\'est pas valide !';
			}							
		}
		else
		{
			$message = 'Le pseudo ne doit pas dépasser 25 caractères ! ';
		}

		$this->inscriptionView($message);
	}

	public function sendEmailActivation($post, $activation_code)
	{
		$subject = "Bienvenue sur mon blog";
		$headers = "From: " . BLOG_AUTHOR . "\r\n";
		$message = "Bonjour " . $post->get('pseudo') . ", bienvenue sur mon blog !\r\n
					Pour activer votre compte, veuillez cliquer sur le lien ci-dessous
					ou copier/coller dans votre navigateur Internet.\r\n
					http://blogphp.charlottesaury.fr/index.php?action=activation&email=" . $post->get('email') . "&key=" . $activation_code . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$message = wordwrap($message, 70, "\r\n");
		mail($post->get('email'), $subject, $message, $headers);
	}

	public function userActivation(Parameter $get)
	{
		if ($this->checkEmail($get->get('email')))
		{
			$user = $this->userManager->getUser($userId = null, $get->get('email'));
			var_dump($user);

			if ($user->actCode() != null)
			{
				if ($user->actCode() != $get->get('key'))
				{
					$message = 'La clé d\'activation n\'est pas bonne, veuillez retourner sur votre mail d\'activation.';
				}
				else
				{
					$this->userManager->userActivation($get->get('email'));
					$message = 'Votre compte est maintenant activé, vous pouvez vous connecter ! ';
				}
			}
			else
			{
				$message = 'Votre compte est déjà activé, vous pouvez vous connecter ! ';
			}
		}
		else
		{
			$message = 'Le lien d\'activation n\'est pas bon, veuillez retourner sur votre mail d\'activation.';
		}

		$this->connexionView($message);
	}

	public function connexion(Parameter $post)
	{
		if ($this->checkEmail($post->get('email')))
		{
			$user = $this->userManager->getUser($userId = null, $post->get('email'));

			if ($user->actCode() != null)
			{
				$message = 'Votre compte n\'est pas activé. Veuillez cliquer sur le lien d\'activation qui vous a été envoyé sur votre adresse email lors de votre inscription.';
			}
			else
			{
				$user_pass = $this->getPassword($post->get('email'));

				if (password_verify($post->get('pass'), $user_pass))
				{
					if ($post->get('rememberme'))
					{
						setcookie('email', $post->get('email'), time() + 365*24*3600, null, null, false, true);
						setcookie('auth', password_hash($post->get('email'), PASSWORD_DEFAULT) . '-----' . password_hash($_SERVER['REMOTE_ADDR'], PASSWORD_DEFAULT), time() + 365*24*3600, null, null, false, true);
					}

					$this->newUserSession($post->get('email'));
					$message = null;
				}
				else
				{
					$message = "L'identifiant et/ou le mot de passe sont erronés.";
				}
			}							
		}				
		else
		{
			$message = "L'adresse email saisie est inconnue";
		}
		return $message;
	}

	public function getPassword($email)
	{
		$user = $this->userManager->getUser($userId = null, $email);
		return $user->password();
	}

	public function newUserSession($email)
	{
		$user = $this->userManager->getUser($userId = null, $email);

		$this->request->getSession()->set('id', $user->id());
		$this->request->getSession()->set('pseudo', $user->pseudo());
        $this->request->getSession()->set('role', $user->userRoleId());
        $this->request->getSession()->set('avatar', $user->avatar());
       	$this->request->getSession()->set('email', $email);
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
		return $this->view->render('frontend', 'forgotPassView', ['message' => $message,
			'session' => $this->request->getSession()]);
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

		return $message = "Un email contenant un lien de réinitialisation de mot de passe a été envoyé à votre adresse email.";
	}

	public function getNewPassCode($email)
	{
		$user = $this->userManager->getUser($userId = null, $email);
		return $user->reinitCode();
	}

	public function newPassView($email, $message = null, $status)
	{
		return $this->view->render('frontend', 'newPassView', ['message' => $message, 'email' => $email, 'status' => $status,
			'session' => $this->request->getSession()]);
	}

	public function checkReinitCode(Parameter $get)
	{
		if ($this->checkEmail($get->get('email')))
		{
			$user_reinit_code = $this->getNewPassCode($get->get('email'));
			var_dump($user_reinit_code);

			if ($get->get('key') != $user_reinit_code)
			{
				$message = 'La clé de réinitialisation n\'est pas bonne, veuillez retourner sur votre mail.';
				$status = false;
			}
			else
			{
				$message = 'Veuillez entrer votre nouveau mot de passe';
				$status = true;
			}
		}
		else
		{
			$message = 'Le lien de réinitialisation n\'est pas bon, veuillez retourner sur votre mail.';
			$status = false;
		}
		$this->newPassView($get->get('email'), $message, $status);
	}

	public function newPass(Parameter $post)
	{
		if ($post->get('pass1') == $post->get('pass2'))
		{
			$newPass = password_hash($post->get('pass1'), PASSWORD_DEFAULT);

			$this->userManager->newUserPass($post->get('email'), $newPass);
			$message = 'Votre mot de passe a été réinitialisé. Vous pouvez maintenant vous connecter ! ';
			$this->connexionView($message);
		}
		else
		{
			$message = 'Les mots de passe saisis sont différents ! ';
			$this->newPassView($post->get('email'), $message, true);
		}
	}
}
