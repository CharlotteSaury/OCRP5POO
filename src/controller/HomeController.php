<?php

namespace src\controller;

use src\controller\Controller;
use config\Request;
use config\Parameter;

class HomeController extends Controller

{
	// Home page

	public function indexView($message = null) 
	{
		return $this->view->render('frontend', 'indexView', ['message' => $message,
			'session' => $this->request->getSession()]); 
	}
	
	public function legalView()
	{
		return $this->view->render('frontend', 'legalView',
			['session' => $this->request->getSession()]);
	}

	public function confidentialityView()
	{
		return $this->view->render('frontend', 'confidentialityView',
			['session' => $this->request->getSession()]);
	}

	public function newContactForm(Parameter $post)
	{
		$contactId = $this->contactManager->addNewContact($post);
		return $contactId;
	}

	public function mailContactForm(Parameter $post, $contactId)
	{
		// Email sent to administrator

		$mailSubject = "Blog : Nouveau message via le formulaire de contact.";
		$mailMessage = "De : " . $post->get('name') . " <" . $post->get('email') . ">\r\n
					Objet : " . $post->get('subject') . "\r\n
					Message : " . $post->get('content') . "\r\n\r\n
					Pour y répondre, cliquez sur le lien suivant : http://www.blogphp.charlottesaury.fr/index.php?action=contactView&id=" . $contactId . "\r\n\r\n
					----------------------\r\n
					Ceci est un mail automatique, Merci de ne pas y répondre.";

		$mailMessage = wordwrap($mailMessage, 70, "\r\n");
		mail(CF_EMAIL, $mailSubject, $mailMessage);


		// Confitmation email sent to sender

		$mailSubject = "Votre message sur le blog de Charlotte SAURY";
		$headers = "From: " . BLOG_AUTHOR . "\r\n";
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

	public function pictureUpload($namePicture)
	{
		if (isset($_FILES[$namePicture]))
		{
			if ($_FILES[$namePicture]['error'] == 0)
			{
				if ($_FILES[$namePicture]['size'] <= 2000000)
				{
					$fileInfos = pathinfo($_FILES[$namePicture]['name']);
					$extension_upload = $fileInfos['extension'];
					$allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');

					if (in_array($extension_upload, $allowed_extensions))
					{
						$uploadResults = 'public/uploads/' . microtime(true) . '_' . basename($_FILES[$namePicture]['name']);

						move_uploaded_file($_FILES[$namePicture]['tmp_name'], $uploadResults);
					}
					else
					{
						$uploadResults = 'L\'extension du fichier n\'est pas acceptée, merci de ne charger que des fichiers .jpg, .jpeg, .gif ou .png';
					}
				}
				else
				{
					$uploadResults = 'Fichier trop volumineux, merci de ne pas dépasser 2Mo.';
				}
			}
			elseif ($_FILES[$namePicture]['error'] == 1 || $_FILES[$namePicture]['error'] == 2)
			{
				$uploadResults = 'Fichier trop volumineux, merci de ne pas dépasser 2Mo.';
			}
			else
			{
				$uploadResults = 'Erreur. Le fichier n\'a pu être téléchargé.';
			}
		}
		else
		{
			$uploadResults = 'Aucun fichier téléchargé.';
		}

		return $uploadResults;
	}
	
}


