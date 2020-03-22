<?php

namespace Src\Controller;

use Src\Controller\Controller;
use Src\Config\Request;
use Src\Config\Parameter;
use Src\Config\File;

/**
 * Class HomeController
 * Manage router to appropriate manager redirection associated with frontend actions
 */
class HomeController extends Controller
{
	/**
	 * Home page
	 * @return void          [return home page view]
	 */
	public function indexView() 
	{
		return $this->view->render('frontend', 'indexView', 
			['session' => $this->request->getSession()]); 
	}
	
	/**
	 * Legal Mention page
	 * @return void [Legal Mention page]
	 */
	public function legalView()
	{
		return $this->view->render('frontend', 'legalView',
			['session' => $this->request->getSession()]);
	}

	/**
	 * Confidentiality policy page
	 * @return void [Confidentiality policy page]
	 */
	public function confidentialityView()
	{
		return $this->view->render('frontend', 'confidentialityView',
			['session' => $this->request->getSession()]);
	}

	/**
	 * Check validity of contact form input, call contactManager to add new contact in database and call mailContactForm method to send a confirmation email to user and notification to administrator.
	 * @param  Parameter $post [user name, email, subject and message]
	 * @return void [Redirect to indexView with confirmation]
	 */
	public function newContactForm(Parameter $post)
	{
		$errors = $this->validation->validate($post, 'Contact');

		if (!$errors) {

			$contactId = $this->contactManager->addNewContact($post);
			$this->mailContactForm($post, $contactId);
			$this->request->getSession()->set('message', "Votre message a bien été envoyé. Nous vous remercions et vous recontacterons dans les plus brefs délais.");
			$this->indexView();
		
		} else {
			$this->request->getSession()->set('errors', $errors);
			header('Location: index.php#contact-form');
		}
		
	}

	/**
	 * Send confirmation email to user after contact form user and notification email to administrator.
	 * @param  Parameter $post      [name, email, subject, message]
	 * @param  int    $contactId
	 * @return void               
	 */
	public function mailContactForm(Parameter $post, $contactId)
	{
		// Notification email sent to administrator

		$mailSubject = "Blog : Nouveau message via le formulaire de contact.";
		$headers = "From: " . BLOG_AUTHOR . "\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$mailMessage = "De : " . $post->get('name') . " <" . $post->get('email') . ">\r\n
					Objet : " . $post->get('subject') . "\r\n
					Message : " . $post->get('content') . "\r\n\r\n
					Pour y répondre, cliquez sur le lien suivant : http://www.blogphp.charlottesaury.fr/index.php?action=contactView&id=" . $contactId . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$mailMessage = wordwrap($mailMessage, 70, "\r\n");
		mail(CF_EMAIL, $mailSubject, $mailMessage, $headers);


		// Confitmation email sent to sender

		$mailSubject = "Votre message sur le blog de Charlotte SAURY";
		$headers = "From: " . BLOG_AUTHOR . "\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$mailMessage = "Bonjour " . $post->get('name') .  ",\r\n
					Votre message ci-dessous a bien été envoyé à l'auteur du blog. Nous vous remercions pour ce contact et tâcherons d'y répondre dans les plus brefs délais.\r\n
					----------------------\r\n
					Objet : " . $post->get('subject') . "\r\n
					Message : " . $post->get('content') . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$mailMessage = wordwrap($mailMessage, 70, "\r\n");
		mail($post->get('email'), $mailSubject, $mailMessage, $headers);
	}

	/**
	 * Upload picture in public folder
	 * @param  File $file [files informations]
	 * @param  string $namePicture [name of file input]
	 * @return sring $uploadResults [picture url]              
	 *
	 */
	public function pictureUpload(File $file, $namePicture)
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
		$random_code = substr(str_shuffle($permitted_chars), 0, 10);
		$extension = pathinfo($file->get($namePicture, 'name'), PATHINFO_EXTENSION);
		$uploadResults = 'public/uploads/' . microtime(true) . '_' . $random_code . '.' . $extension;
		move_uploaded_file($file->get($namePicture, 'tmp_name'), $uploadResults);
		return $uploadResults;		
	}
}


