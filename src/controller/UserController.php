<?php

namespace Src\Controller;

use Src\Controller\Controller;
use Src\Controller\HomeController;
use Config\Request;
use Config\Parameter;
use Config\Session;

/**
 * Class UserController
 * Manage router to appropriate manager redirection associated with user actions
 */
class UserController extends Controller

{
	/**
	 * Check if user is admin or super-admin
	 * @return bool [return false if user is neither admin nor super-admin or unlogged]
	 */
	public function adminAccess()
	{
		if ($this->request->getSession()->get('id')) {

			$userId = $this->request->getSession()->get('id');
			$user = $this->userManager->getUser($userId);

			if ($user->getUserRoleId() == 1 || $user->getUserRoleId() == 3) {
				return true;
			}
			return false;
		}
		return false;
	}

	/**
	 * Return inscription page
	 * @param  array $errors  [errors such as form input validity]
	 * @return void [return inscription page view]
	 */
	public function inscriptionView($errors = null) 
	{
		return $this->view->render('frontend', 'inscriptionView',
			['session' => $this->request->getSession(),
			'errors' => $errors]);
	}

	/**
	 * Return connexion page
	 * @return void         [return connexion view]
	 */
	public function connexionView() 
	{
		return $this->view->render('frontend', 'connexionView',
			['session' => $this->request->getSession()]);
	}

	/**
	 * Call userManager to check if a pseudo exists in database and if this pseudo is associated with userId (if provided)
	 * @param  string $pseudo [pseudo]
	 * @param  int $userId [userId]
	 * @return bool [return true if pseudo exists in database, false if not]
	 */
	/*public function checkPseudo($pseudo, $userId = null)
	{
		return $pseudoExists = ($this->userManager->pseudoExists($pseudo, $userId) == 1) ? true : false;
	}*/

	/**
	 * Call userManager to check if an email exists in database and if this email is associated with userId (if provided)
	 * @param  string $email [email]
	 * @param  int $userId [userId]
	 * @return bool [return true if email exists in database, false if not]
	 */
	public function checkEmail($email, $userId = null)
	{
		return ($this->userManager->emailExists($email, $userId) == 1) ? true : false;
	}

	/**
	 * Check inscription form input validity and call userMManager to create new user in database. Call sendEmailActivation method to send activation email to user. Redirect to inscriptionView page.
	 * @param  Parameter $post [pseudo, email, password]
	 * @return void          [description]
	 */
	public function inscription(Parameter $post)
	{
		$errors = $this->validation->validate($post, 'Inscription');

		if (!$errors) {

			if ($post->get('pass1') == $post->get('pass2')) {

				$post->set('pass', password_hash($post->get('pass1'), PASSWORD_DEFAULT));

				// Activation_code generation
				$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
				$random_code = substr(str_shuffle($permitted_chars), 0, 10);
				$activation_code = password_hash($random_code, PASSWORD_DEFAULT);
				$this->userManager->addUser($post, $activation_code);
				$this->sendEmailActivation($post, $activation_code);
				$this->request->getSession()->set('message', 'Merci pour votre inscription ! Un email de confirmation vous a été envoyé afin de confirmer votre adresse email. Merci de vous reporter à cet email pour activer votre compte ! ');
			
			} else {
				$this->request->getSession()->set('message', 'Les mots de passe saisis sont différents ! ');
			}
			$this->inscriptionView();
		} else {
			$this->inscriptionView($errors);
		}
	}

	/**
	 * Send an activation email to user after inscription
	 * @param  Parameter $post [pseudo, email]
	 * @param  string $activation_code
	 * @return void    
	 */
	public function sendEmailActivation(Parameter $post, $activation_code)
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

	/**
	 * Get email and activation key from activation link and call userManager tocheck correspondance in database. Delete activation_code from database to activate user account.
	 * @param  Parameter $get [email, activation_code]
	 * @return void [redirect to connexionView]
	 */
	public function userActivation(Parameter $get)
	{
		if ($this->checkEmail($get->get('email'))) {

			$user = $this->userManager->getUser(null, $get->get('email'));

			if ($user->getActCode() != null) {

				if ($user->getActCode() != $get->get('key')) {
					$this->request->getSession()->set('message', 'La clé d\'activation n\'est pas bonne, veuillez retourner sur votre mail d\'activation.');

				} else {

					$this->userManager->userActivation($get->get('email'));
					$this->request->getSession()->set('message', 'Votre compte est maintenant activé, vous pouvez vous connecter ! ');

				}

			} else {
				$this->request->getSession()->set('message', 'Votre compte est déjà activé, vous pouvez vous connecter ! ');
			}

		} else {
			$this->request->getSession()->set('message', 'Le lien d\'activation n\'est pas bon, veuillez retourner sur votre mail d\'activation.');
		}

		$this->connexionView();
	}

	/**
	 * Check if user account is activated and then concordance between email and password. Create new user session and cookies email and authentification if remember me functionnality is checked.
	 * @param  Parameter $post [email, password]
	 * @return void          [Connect to user account]
	 */
	public function connexion(Parameter $post)
	{
		if ($this->checkEmail($post->get('email'))) {

			$user = $this->userManager->getUser($userId = null, $post->get('email'));

			if ($user->getActCode() != null) {

				$this->request->getSession()->set('message', 'Votre compte n\'est pas activé. Veuillez cliquer sur le lien d\'activation qui vous a été envoyé sur votre adresse email lors de votre inscription.');
			
			} else {

				$user_pass = $this->userManager->getUser($userId = null, $post->get('email'))->getPassword();

				if (password_verify($post->get('pass'), $user_pass)) {

					if ($post->get('rememberme')) {

						setcookie('email', $post->get('email'), time() + 365*24*3600, null, null, false, true);
						setcookie('auth', password_hash($post->get('email'), PASSWORD_DEFAULT) . '-----' . password_hash($_SERVER['REMOTE_ADDR'], PASSWORD_DEFAULT), time() + 365*24*3600, null, null, false, true);
					}

					$this->newUserSession($post->get('email'));
					header('Location: index.php');
				
				} else {
					$this->request->getSession()->set('message', "L'identifiant et/ou le mot de passe sont erronés.");
					$this->connexionView();
				}
			}

		} else {
			$this->request->getSession()->set('message', "L'identifiant et/ou le mot de passe sont erronés.");
			$this->connexionView();
		}

	}


	/**
	 * Create new user session variables (id, pseudo, role, avatar and email)
	 * @param  string $email
	 * @return void
	 */
	public function newUserSession($email)
	{
		$user = $this->userManager->getUser(null, $email);
		$this->request->getSession()->set('id', $user->getId());
		$this->request->getSession()->set('pseudo', $user->getPseudo());
        $this->request->getSession()->set('role', $user->getUserRoleId());
        $this->request->getSession()->set('avatar', $user->getAvatar());
       	$this->request->getSession()->set('email', $email);

       	if ($user->getUserRoleId() == 3) {
       		$this->request->getSession()->set('unreadContactsNb', $this->contactManager->getContactsNb(1));
       	}
	}

	

	/**
	 * If user clicked on "Mot de passe oublié" link, return forgot password page
	 * @return void          [return forgot pass view with email input form]
	 */
	public function forgotPassView()
	{
		return $this->view->render('frontend', 'forgotPassView', 
			['session' => $this->request->getSession()]);
	}

	/**
	 * Generate a reinitialization code and call forgotPassMail method to send reinitialization link to user.
	 * @param  string $email
	 * @return void [redirect to newPassView]
	 */
	public function newPassCode($email)
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$random_code = substr(str_shuffle($permitted_chars), 0, 10);
		$reinit_code = password_hash($random_code, PASSWORD_DEFAULT);
		$this->userManager->newPassCode($email, $reinit_code);
		$this->forgotPassMail($email, $reinit_code);
		$this->request->getSession()->set('message', "Un email contenant un lien de réinitialisation de mot de passe a été envoyé à votre adresse email.");
		$this->forgotPassView();
	}

	/**
	 * Send email to user with password reinitialization link
	 * @param  string $email       
	 * @param  string $reinit_code
	 * @return void
	 */
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

	/**
	 * Return new pass view page with form to choose new password
	 * @param  string $email
	 * @param  bool $status [status of new pass request after reinitialization code check]
	 * @return void          [return new pass view]
	 */
	public function newPassView($email, $status)
	{
		return $this->view->render('frontend', 'newPassView',
			['email' => $email, 
			'status' => $status,
			'session' => $this->request->getSession()]);
	}

	/**
	 * Check validity of reinitialization link (variables email and reinitialization key)
	 * @param  Parameter $get [email, reinitialization key]
	 * @return void         [redirect to newPassView page]
	 */
	public function checkReinitCode(Parameter $get)
	{
		$email = $this->request->getSession()->set('email', $get->get('email'));

		if ($this->checkEmail($get->get('email'))) {

			$user_reinit_code = $this->userManager->getUser(null, $get->get('email'))->getReinitCode();

			if ($get->get('key') != $user_reinit_code) {

				$this->request->getSession()->set('message', 'La clé de réinitialisation n\'est pas bonne, veuillez retourner sur votre mail.');
				$status = false;

			} else {

				$this->request->getSession()->set('message', 'Veuillez entrer votre nouveau mot de passe');
				$status = true;
			}

		} else {

			$this->request->getSession()->set('message', 'Le lien de réinitialisation n\'est pas bon, veuillez retourner sur votre mail.');
			$status = false;
		}
		$this->newPassView($email, $status);
	}

	/**
	 * Check new password input validity and update user password.
	 * @param  Parameter $post  [pass1 and pass2]
	 * @param  string    $email
	 * @return void [Reidrect to ConnexionView or newPassView if input errors]
	 */
	public function newPass(Parameter $post, $email)
	{
		$errors = $this->validation->validate($post, 'NewPass');

		if (!$errors) {

			if ($post->get('pass1') == $post->get('pass2')) {

				$newPass = password_hash($post->get('pass1'), PASSWORD_DEFAULT);
				$this->userManager->newUserPass($email, $newPass);
				$this->request->getSession()->set('message', 'Votre mot de passe a été réinitialisé. Vous pouvez maintenant vous connecter ! ');
				$this->connexionView();

			} else {

				$this->request->getSession()->set('message', 'Les mots de passe saisis sont différents ! ');
				$this->newPassView($email, true);
			}

		} else {

			$this->request->getSession()->set('message', $errors['pass1']);
			$this->newPassView($email, true);
		}
	}

}
