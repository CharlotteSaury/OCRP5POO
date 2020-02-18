<?php

namespace controller;

require_once('./model/Manager.php');
require_once('./model/PostManager.php');
require_once('./model/CommentManager.php');
require_once('./model/UserManager.php');
require_once('./model/ContactManager.php');

use model\PostManager;
use model\CommentManager;
use model\UserManager;
use model\ContactManager;

class HomeController

{
	private $_postManager;
	private $_commentManager;
	private $_userManager;
	private $_contactManager;

	public function __construct()
	{
		$this->_postManager = new PostManager();
		$this->_commentManager = new CommentManager();
		$this->_userManager = new UserManager();
		$this->_contactManager = new ContactManager();
	}

	// Home page

	public function indexView($message = null) 
	{
		require('./view/frontend/indexView.php');
	}

	public function newContactForm($name, $email, $subject, $content)
	{
		$contactId = $this->_contactManager->addNewContact($name, $email, $subject, $content);
		return $contactId;
	}

	public function mailContactForm($name, $email, $subject, $content, $contactId)
	{
		$subject = "Blog : Nouveau message via le formulaire de contact.";
		$message = "De : " . $name . " <" . $email . ">\r\n
					Objet : " . $subject . "\r\n
					Message : " . $content . "\r\n\r\n
					Pour y répondre, cliquez sur le lien suivant : http://localhost/OCR-P5-Blog-POO/index.php?action=contactView&id=" . $contactId . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";
		var_dump($message); 

		$message = wordwrap($message, 70, "\r\n");
		mail($email, $subject, $message);
	}
	
}


