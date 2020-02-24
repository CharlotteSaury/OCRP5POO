<?php

namespace controller;

require_once './model/Manager.php';
require_once './model/ContactManager.php';
require_once './view/View.php';

use model\ContactManager;
use view\View;

class HomeController

{
	private $_contactManager,
			$_view;

	public function __construct()
	{
		$this->_contactManager = new ContactManager();
		$this->_view = new View();
	}

	// Home page

	public function indexView($message = null) 
	{
		return $this->_view->render('frontend', 'indexView', ['message' => $message]); 
	}

	public function newContactForm($name, $email, $subject, $content)
	{
		$contactId = $this->_contactManager->addNewContact($name, $email, $subject, $content);
		return $contactId;
	}

	public function mailContactForm($name, $email, $subject, $content, $contactId)
	{
		// Email sent to administrator

		$mailSubject = "Blog : Nouveau message via le formulaire de contact.";
		$mailMessage = "De : " . $name . " <" . $email . ">\r\n
					Objet : " . $subject . "\r\n
					Message : " . $content . "\r\n\r\n
					Pour y répondre, cliquez sur le lien suivant : http://www.blogphp.charlottesaury.fr/index.php?action=contactView&id=" . $contactId . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$mailMessage = wordwrap($mailMessage, 70, "\r\n");
		mail(CF_EMAIL, $mailSubject, $mailMessage);


		// Confitmation email sent to sender

		$mailSubject = "Votre message sur le blog de Charlotte SAURY";
		$headers = "From: " . BLOG_AUTHOR . "\r\n";
		$mailMessage = "Bonjour " . $name .  ",\r\n
					Votre message ci-dessous a bien été envoyé à l'auteur du blog. Nous vous remercions pour ce contact et tâcherons d'y répondre dans les plus brefs délais.\r\n
					----------------------\r\n
					Objet : " . $subject . "\r\n
					Message : " . $content . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$mailMessage = wordwrap($mailMessage, 70, "\r\n");
		mail($email, $mailSubject, $mailMessage, $headers);
	}

	public function legalView()
	{
		return $this->_view->render('frontend', 'legalView');
	}

	public function confidentialityView()
	{
		return $this->_view->render('frontend', 'confidentialityView');
	}
	
}


